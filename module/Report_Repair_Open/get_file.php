<?php
header('Content-Type: application/json');

$upload_dir = './upload/';
$allowed_types = ['jpeg', 'png'];
$images = [];

foreach ($allowed_types as $type) {
    $files = glob($upload_dir . '*.' . $type);
    $images = array_merge($images, array_map('basename', $files));
}

echo json_encode($images);
?>