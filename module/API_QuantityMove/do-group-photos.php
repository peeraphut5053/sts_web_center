<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, Content-Type, X-STS-Token');
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
header('Cache-Control: private, no-store');
header('X-Content-Type-Options: nosniff');
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'OPTIONS') {
    http_response_code(204);
    exit;
}
require_once __DIR__ . '/../../initial.php';
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../include/class.SeaweedFS.php';

function doPhotoQuery($db, $sql, $args) {
    $result = sqlsrv_query($db, $sql, $args);
    if (!$result) {
        $errors = sqlsrv_errors(SQLSRV_ERR_ERRORS) ?: array();
        foreach ($errors as $error) {
            error_log('[DO group photos] SQLSTATE ' . $error['SQLSTATE'] . ', code ' . $error['code']);
        }
        throw new RuntimeException('DO group database is unavailable', 503);
    }
    return $result;
}

function doPhotoStorage($url, $method = 'GET') {
    if (!function_exists('curl_init')) {
        $context = stream_context_create(array('http' => array(
            'method' => $method,
            'header' => "Accept: application/json\r\n",
            'ignore_errors' => true,
            'follow_location' => 0,
            'timeout' => 15,
        )));
        $body = @file_get_contents($url, false, $context);
        if ($body === false || !isset($http_response_header[0])
            || !preg_match('/^HTTP\/\S+\s+(\d{3})/', $http_response_header[0], $match)) {
            throw new RuntimeException('Photo storage is unavailable', 503);
        }
        return array((int) $match[1], $body);
    }
    $request = curl_init($url);
    curl_setopt_array($request, array(
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array('Accept: application/json'),
        CURLOPT_CONNECTTIMEOUT => 4,
        CURLOPT_TIMEOUT => 15,
    ));
    $body = curl_exec($request);
    $status = curl_getinfo($request, CURLINFO_HTTP_CODE);
    curl_close($request);
    if ($body === false) throw new RuntimeException('Photo storage is unavailable', 503);
    return array($status, $body);
}

function doPhotoEntries($directory, $onlyCheck = false) {
    $cursor = '';
    $seen = array();
    $photos = array();
    do {
        list($status, $body) = doPhotoStorage($directory . '?limit=' . ($onlyCheck ? '1' : '100')
            . '&lastFileName=' . rawurlencode($cursor));
        if ($status === 404 && $cursor === '') return array();
        $page = json_decode($body, true);
        if ($status !== 200 || !is_array($page) || !array_key_exists('Entries', $page)
            || !isset($page['ShouldDisplayLoadMore']) || !is_bool($page['ShouldDisplayLoadMore'])
            || ($page['Entries'] !== null && !is_array($page['Entries']))) {
            throw new RuntimeException('Cannot list photos in storage', 502);
        }
        if ($onlyCheck && !empty($page['Entries'])) return $page['Entries'];
        foreach ($page['Entries'] ?: array() as $entry) {
            $path = basename(isset($entry['FullPath']) ? $entry['FullPath'] : '');
            if (doPhotoValidName($path)) {
                $photos[] = array('path' => $path, 'createdate' => isset($entry['Crtime']) ? $entry['Crtime'] : null);
            }
        }
        if (!$page['ShouldDisplayLoadMore']) break;
        $cursor = isset($page['LastFileName']) ? $page['LastFileName'] : '';
        if ($cursor === '' || isset($seen[$cursor])) throw new RuntimeException('Cannot list photos in storage', 502);
        $seen[$cursor] = true;
    } while (true);
    usort($photos, function ($a, $b) { return strcmp($b['createdate'] ?: '', $a['createdate'] ?: ''); });
    return $photos;
}

function doPhotoValidName($name) {
    return is_string($name) && preg_match('/\A[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12}\.(jpg|png|webp)\z/', $name);
}

$transaction = false;
$tempFile = null;
try {
    $authorization = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION']
        : (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION']) ? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] : '');
    try {
        $token = isset($_SERVER['HTTP_X_STS_TOKEN']) ? $_SERVER['HTTP_X_STS_TOKEN'] : '';
        if ($token === '' && preg_match('/\ABearer ([^\s]+)\z/i', $authorization, $match)) {
            $token = $match[1];
        }
        if ($token === '') throw new RuntimeException();
        $session = JWT::decode($token, new Key($key, 'HS256'));
        if (empty($session->user) || empty($session->exp)) throw new RuntimeException();
    } catch (Throwable $error) {
        throw new RuntimeException('Please sign in again', 401);
    }

    $id = filter_var(isset($_GET['id']) ? $_GET['id'] : null, FILTER_VALIDATE_INT,
        array('options' => array('min_range' => 1, 'max_range' => 2147483647)));
    if (!$id) throw new RuntimeException('Invalid DO group', 400);
    $action = isset($_GET['action']) ? $_GET['action'] : 'list';
    if (!(($method === 'GET' && in_array($action, array('list', 'view'), true))
        || ($method === 'PUT' && $action === 'upload')
        || ($method === 'POST' && in_array($action, array('delete-photo', 'delete-group'), true)))) {
        throw new RuntimeException('Invalid photo action', 405);
    }
    if ($action === 'upload') {
        $maxBytes = 10 * 1024 * 1024;
        if (isset($_SERVER['CONTENT_LENGTH']) && (int) $_SERVER['CONTENT_LENGTH'] > $maxBytes) {
            throw new RuntimeException('Maximum photo size is 10 MB', 413);
        }
        $bytes = file_get_contents('php://input', false, null, 0, $maxBytes + 1);
        if ($bytes === false || $bytes === '') throw new RuntimeException('Please select an image to upload', 400);
        if (strlen($bytes) > $maxBytes) throw new RuntimeException('Maximum photo size is 10 MB', 413);
        $image = @getimagesizefromstring($bytes);
        $extensions = array('image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp');
        if (!$image || !isset($extensions[$image['mime']])) throw new RuntimeException('Only JPG, PNG and WebP are supported', 415);
        $tempFile = tempnam(sys_get_temp_dir(), 'do-photo-');
        if (!$tempFile || file_put_contents($tempFile, $bytes) !== strlen($bytes)) {
            throw new RuntimeException('Cannot prepare photo upload', 500);
        }
        unset($bytes);
    }
    if ($method !== 'GET') {
        if (!sqlsrv_begin_transaction($ConnSL)) throw new RuntimeException('Cannot start operation', 500);
        $transaction = true;
        $lock = doPhotoQuery($ConnSL, "SET NOCOUNT ON; DECLARE @result int;
            EXEC @result = sp_getapplock @Resource = ?, @LockMode = 'Exclusive',
            @LockOwner = 'Transaction', @LockTimeout = 10000; SELECT @result AS lockResult;",
            array('shipping:do-group:' . $id));
        $lockRow = sqlsrv_fetch_array($lock, SQLSRV_FETCH_ASSOC);
        sqlsrv_free_stmt($lock);
        if (!$lockRow || $lockRow['lockResult'] < 0) throw new RuntimeException('DO group is busy, please retry', 409);
    }

    $group = doPhotoQuery($ConnSL, 'SELECT id FROM dbo.STS_list_of_do_group WHERE id = ?', array($id));
    $exists = sqlsrv_fetch_array($group, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($group);
    if (!$exists) throw new RuntimeException('DO group not found', 404);
    $folder = 'DO-GROUP-PHOTOS/' . $id;
    $directory = SeaweedFS::getUrl($folder, '');
    $path = isset($_GET['path']) ? $_GET['path'] : '';
    $data = null;

    switch ($action) {
        case 'list':
            $data = doPhotoEntries($directory);
            break;
        case 'view':
        case 'delete-photo':
            if (!doPhotoValidName($path)) throw new RuntimeException('Photo not found', 404);
            list($status, $body) = doPhotoStorage(SeaweedFS::getUrl($folder, $path), $action === 'view' ? 'GET' : 'DELETE');
            if ($action === 'view') {
                if ($status !== 200) throw new RuntimeException('Cannot open photo', $status === 404 ? 404 : 502);
                header('Content-Type: ' . SeaweedFS::getMimeType($path));
                echo $body;
                exit;
            }
            if ($status !== 404 && ($status < 200 || $status >= 300)) throw new RuntimeException('Cannot delete photo', 502);
            break;
        case 'upload':
            $hex = bin2hex(random_bytes(16));
            $path = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split($hex, 4)) . '.' . $extensions[$image['mime']];
            list($status) = doPhotoStorage($directory . '?metadata=true');
            if ($status === 404) {
                list($status) = doPhotoStorage($directory, 'POST');
            }
            if ($status < 200 || $status >= 300 || !SeaweedFS::upload($folder, $path, $tempFile)) {
                throw new RuntimeException('SeaweedFS could not save the photo', 502);
            }
            $data = array('path' => $path);
            break;
        case 'delete-group':
            if (doPhotoEntries($directory, true)) throw new RuntimeException('Remove all photos before deleting this DO group', 409);
            sqlsrv_free_stmt(doPhotoQuery($ConnSL, 'DELETE FROM dbo.STS_list_of_do_group WHERE id = ?', array($id)));
            break;
    }

    if ($transaction && !sqlsrv_commit($ConnSL)) throw new RuntimeException('Cannot complete operation', 500);
    $transaction = false;
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array('success' => true, 'data' => $data));
} catch (Throwable $error) {
    if ($transaction) sqlsrv_rollback($ConnSL);
    $status = (int) $error->getCode();
    if ($status < 400 || $status > 599) $status = 500;
    if ($status >= 500) error_log('[DO group photos] ' . $error->getMessage());
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array('success' => false, 'error' => $status === 500 ? 'Cannot manage DO group photos' : $error->getMessage()));
} finally {
    if ($tempFile) unlink($tempFile);
}
