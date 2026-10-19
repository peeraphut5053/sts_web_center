<?php
header('Content-Type: application/json; charset=utf-8');
if (session_status() === PHP_SESSION_NONE) session_start();
require __DIR__ . '/../../initial.php';
require_once __DIR__ . '/storage.php';
$filename = '';
$transaction = false;
$committed = false;
try {
    $tag = packingTag(isset($_POST['tag_id']) ? trim($_POST['tag_id']) : '');
    $slot = packingSlot(isset($_POST['slot']) ? $_POST['slot'] : '');
    $file = isset($_FILES['file']) ? $_FILES['file'] : null;
    if (!$file || $file['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) throw new Exception('Please select an image to upload');
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $types = array('jpg' => IMAGETYPE_JPEG, 'jpeg' => IMAGETYPE_JPEG, 'png' => IMAGETYPE_PNG, 'gif' => IMAGETYPE_GIF);
    $image = @getimagesize($file['tmp_name']);
    if (!isset($types[$extension]) || !$image || $image[2] !== $types[$extension]) throw new Exception('Only JPG, PNG and GIF images are supported');
    if (!sqlsrv_begin_transaction($ConnSL)) throw new Exception('Cannot start upload');
    $transaction = true;
    $stmt = packingQuery($ConnSL, 'SELECT file_name_1, file_name_2 FROM Mv_Bc_Tag_img_upload WITH (UPDLOCK, HOLDLOCK) WHERE tag_id = ?', array($tag));
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($stmt);
    if (!$row) throw new Exception('Please scan a valid Tag barcode first');
    $filename = 'PK_' . substr($tag, 0, 17) . '_' . substr($slot, -1) . '_' . date('Ymd_His') . '_' . bin2hex(random_bytes(6)) . '.' . $extension;
    if (!SeaweedFS::upload('DO-PHOTO-PACKING', $filename, $file['tmp_name'])) throw new Exception('Cannot upload photo to SeaweedFS');
    $user = isset($_POST['create_by']) ? trim($_POST['create_by']) : '';
    $stmt = packingQuery($ConnSL, "UPDATE Mv_Bc_Tag_img_upload SET $slot = ?, create_by = ? WHERE tag_id = ?", array($filename, $user, $tag));
    sqlsrv_free_stmt($stmt);
    if (!sqlsrv_commit($ConnSL)) throw new Exception('Cannot save packing photo');
    $committed = true;
    $warning = '';
    try { packingDeleteFile($row[$slot]); }
    catch (Exception $e) { $warning = 'New photo saved, but the previous photo could not be removed from SeaweedFS'; }
    echo json_encode(array('success' => true, 'filename' => $filename, 'warning' => $warning));
} catch (Exception $e) {
    if ($transaction && !$committed) sqlsrv_rollback($ConnSL);
    if ($filename !== '' && !$committed) SeaweedFS::delete('DO-PHOTO-PACKING', $filename);
    http_response_code(400);
    echo json_encode(array('success' => false, 'message' => $e->getMessage()));
}
