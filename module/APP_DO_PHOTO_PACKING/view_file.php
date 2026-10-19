<?php
require_once __DIR__ . '/../../include/class.SeaweedFS.php';

$file = isset($_GET['file']) && is_string($_GET['file']) ? $_GET['file'] : '';
if ($file === '' || $file !== basename(str_replace('\\', '/', $file))
    || !in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), array('jpg', 'jpeg', 'png', 'gif'), true)) {
    http_response_code(400);
    exit('Invalid file');
}

$mime = SeaweedFS::getMimeType($file);
if (SeaweedFS::stream('DO-PHOTO-PACKING', $file, $mime)) {
    exit;
}

http_response_code(404);
echo 'File not found';
