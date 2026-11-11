<?php
// ตั้งค่า CORS ถ้าจำเป็น
// Prevent included output or PHP warnings from corrupting JSON.
$qaBufferLevel = ob_get_level();
ob_start();
ini_set('display_errors', '0');
$qaResponseSent = false;
function qaUploadRespond($response, $status = 200) {
    global $qaBufferLevel, $qaResponseSent;
    $flags = JSON_UNESCAPED_UNICODE;
    if (defined('JSON_INVALID_UTF8_SUBSTITUTE')) {
        $flags |= constant('JSON_INVALID_UTF8_SUBSTITUTE');
    }
    $json = json_encode($response, $flags);
    if ($json === false) {
        error_log('[QA upload] JSON encoding failed: ' . json_last_error_msg());
        $json = '{"success":false,"message":"Unable to encode upload response. Please check the server log."}';
        $status = 500;
    }
    while (ob_get_level() > $qaBufferLevel) ob_end_clean();
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    $qaResponseSent = true;
    echo $json;
}
register_shutdown_function(function () {
    global $qaResponseSent;
    if (!$qaResponseSent) {
        error_log('[QA upload] Request ended without a JSON response; check PHP error log');
        qaUploadRespond(array('success' => false, 'message' => 'Upload interrupted. Please check the server log.'), 500);
    }
});

try {
$code = isset($_POST['code']) && is_string($_POST['code']) ? trim($_POST['code']) : '';
$old_file = isset($_POST['old_file']) && is_string($_POST['old_file']) ? trim($_POST['old_file']) : '';
$response = array('success' => false, 'message' => 'Upload failed');
$status = 400;

if (isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $response = array();
    $allowed_types = ['pdf', 'xls', 'xlsx'];

    // ตรวจสอบข้อผิดพลาด
    if (isset($file['error']) && !is_array($file['error']) && $file['error'] === UPLOAD_ERR_OK) {
        if (!isset($file['name']) || !is_string($file['name']) || !preg_match('//u', $file['name'])) {
            throw new InvalidArgumentException('Invalid filename encoding. Please rename the file and retry.');
        }
        // ตรวจสอบนามสกุลไฟล์
        $file_type = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (in_array($file_type, $allowed_types)) {
            $new_filename = basename(str_replace('\\', '/', $file['name']));
            if (empty($code)) {
                $response['success'] = false;
                $response['message'] = 'Missing document code';
            } else {
                if (!is_uploaded_file($file['tmp_name'])) {
                    throw new InvalidArgumentException('Uploaded temporary file is unavailable. Please retry.');
                }
                require __DIR__ . '/../../initial.php';
                require_once __DIR__ . '/../../include/class.SeaweedFS.php';
                if (!SeaweedFS::upload('QA-Document', $new_filename, $file['tmp_name'])) {
                    throw new RuntimeException('Unable to upload the file to document storage. Please retry or contact the administrator.');
                }
                $saved = sqlsrv_query($ConnSL,
                    'UPDATE STS_QA_Document SET [file] = ? WHERE Code = ?',
                    array($new_filename, $code));
                if ($saved === false) {
                    foreach (sqlsrv_errors() ?: array() as $sqlError) {
                        error_log('[QA upload] SQL update error ' . $sqlError['SQLSTATE'] . '/' . $sqlError['code'] . ': ' . $sqlError['message']);
                    }
                    throw new RuntimeException('File uploaded, but the document attachment update failed. Please check the server log.');
                }
                // NOCOUNT can make rows_affected return -1 after a successful UPDATE.
                sqlsrv_free_stmt($saved);
                $verified = sqlsrv_query($ConnSL,
                    'SELECT [file] FROM STS_QA_Document WHERE Code = ?',
                    array($code));
                if ($verified === false) {
                    foreach (sqlsrv_errors() ?: array() as $sqlError) {
                        error_log('[QA upload] SQL verification error ' . $sqlError['SQLSTATE'] . '/' . $sqlError['code'] . ': ' . $sqlError['message']);
                    }
                    throw new RuntimeException('File uploaded, but the saved attachment could not be verified. Please check the server log.');
                }
                $document = sqlsrv_fetch_array($verified, SQLSRV_FETCH_ASSOC);
                sqlsrv_free_stmt($verified);
                if ($document === false) {
                    error_log('[QA upload] Failed to fetch attachment verification result');
                    throw new RuntimeException('File uploaded, but the saved attachment could not be verified. Please check the server log.');
                }
                if ($document === null) {
                    qaUploadRespond(array('success' => false, 'message' => 'File uploaded, but the document code was not found. Please refresh the document list and retry.'), 404);
                    return;
                }
                if (isset($document['file']) && $document['file'] === $new_filename) {
                    $response['success'] = true;
                    $status = 200;
                    $response['message'] = 'Upload successful';
                    $response['file_url'] = 'module/RPT_QA_Document/view_file.php?file=' . rawurlencode($new_filename);
                    // Remove the previous attachment only after the replacement is saved.
                    if (!empty($old_file) && $old_file !== 'null') {
                        $old_name = basename(str_replace('\\', '/', $old_file));
                        if ($old_name !== $new_filename) {
                            try {
                                if (!SeaweedFS::delete('QA-Document', $old_name)) error_log('[QA upload] Previous attachment cleanup failed');
                            } catch (Throwable $cleanupError) {
                                error_log('[QA upload] Previous attachment cleanup threw ' . get_class($cleanupError));
                            }
                        }
                    }
                } else {
                    $status = 500;
                    error_log('[QA upload] Saved attachment filename does not match the uploaded filename');
                    $response['success'] = false;
                    $response['message'] = 'File uploaded, but the saved attachment filename does not match. Please check the server log.';
                }
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'Please select a PDF, XLS or XLSX file.';
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'File upload failed (error ' . (isset($file['error']) && !is_array($file['error']) ? $file['error'] : 'invalid upload') . '). Check the server upload size limit and temporary directory.';
    }
} else {
    $response['success'] = false;
    $response['message'] = 'No file received. Check the server upload and POST size limits.';
}

// ส่งคืนข้อมูล JSON
qaUploadRespond($response, $status);
} catch (InvalidArgumentException $error) {
    qaUploadRespond(array('success' => false, 'message' => $error->getMessage()), 400);
} catch (Throwable $error) {
    error_log('[QA upload] ' . get_class($error) . ': ' . $error->getMessage());
    $message = $error instanceof RuntimeException ? $error->getMessage() : 'Upload failed. Please check the server log.';
    qaUploadRespond(array('success' => false, 'message' => $message), 500);
}
