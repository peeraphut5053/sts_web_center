<?php
require_once __DIR__ . '/../../include/class.SeaweedFS.php';
function packingQuery($conn, $sql, $params = array()) {
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) throw new Exception('Cannot save or read packing photo data');
    return $stmt;
}
function packingSlot($slot) {
    if (!in_array($slot, array('file_name_1', 'file_name_2'), true)) throw new Exception('Invalid image slot');
    return $slot;
}
function packingTag($tag) {
    if (!is_string($tag) || !preg_match('/^[0-9]{1,30}$/D', $tag)) throw new Exception('Invalid Tag barcode');
    return $tag;
}
function packingDeleteFile($filename) {
    if ($filename !== '' && $filename !== null && !SeaweedFS::delete('DO-PHOTO-PACKING', basename($filename))) {
        throw new Exception('Cannot delete photo from SeaweedFS');
    }
}
