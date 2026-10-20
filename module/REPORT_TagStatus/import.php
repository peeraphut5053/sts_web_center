<?php
header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(array('success' => false, 'error' => 'POST required'));
    exit;
}
$rows = json_decode(isset($_POST['rows']) ? $_POST['rows'] : '', true);
if (!is_array($rows) || count($rows) < 1 || count($rows) > 1000) {
    echo json_encode(array('success' => false, 'error' => 'Expected 1-1000 rows'));
    exit;
}
require_once '../initial.php';
$loader = new CallModel();
$loader->SyteLine_Models();
$model = new BcTag();
$model->setConn($ConnSL);
echo json_encode($model->ImportTagStatus($rows));
