<?php
// Only the authenticated PHP session may grant count-date editing permission.
session_start();
header('Content-Type: application/json; charset=UTF-8');
$username = isset($_SESSION['login_username']) ? strtolower($_SESSION['login_username']) : '';
$canEdit = in_array($username, array('pittawat', 'admin'), true);
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($canEdit && empty($_SESSION['count_date_csrf'])) $_SESSION['count_date_csrf'] = bin2hex(random_bytes(32));
    echo json_encode(array('can_edit' => $canEdit, 'csrf_token' => $canEdit ? $_SESSION['count_date_csrf'] : null));
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}
if (!$canEdit) {
    http_response_code(403);
    echo json_encode(array('success' => false, 'message' => 'No permission to edit count date'));
    exit;
}
if (!isset($_POST['csrf_token'], $_SESSION['count_date_csrf']) || !is_string($_POST['csrf_token'])
    || !hash_equals($_SESSION['count_date_csrf'], $_POST['csrf_token'])) {
    http_response_code(403);
    echo json_encode(array('success' => false, 'message' => 'Invalid session token. Please reload.'));
    exit;
}
$do_num = isset($_POST['do_num']) && is_string($_POST['do_num']) ? trim($_POST['do_num']) : '';
$created = isset($_POST['record_createdate']) && is_string($_POST['record_createdate']) ? $_POST['record_createdate'] : '';
$count_date = isset($_POST['count_date']) && is_string($_POST['count_date']) ? $_POST['count_date'] : '';
$date = DateTime::createFromFormat('!Y-m-d', $count_date);
if ($do_num === '' || !preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{3}$/', $created)
    || !$date || $date->format('Y-m-d') !== $count_date || (int)substr($count_date, 0, 4) < 1) {
    http_response_code(400);
    echo json_encode(array('success' => false, 'message' => 'Invalid record or count date'));
    exit;
}
try {
    include '../../initial.php';
    $loader = new CallModel();
    $loader->SyteLine_Models();
    $model = new API_FreeZone();
    $model->setConn($ConnSL);
    $model->UpdateCountPipeDate($do_num, $created, $count_date);
    echo json_encode(array('success' => true));
} catch (Exception $e) {
    http_response_code(409);
    echo json_encode(array('success' => false, 'message' => $e->getMessage()));
}
