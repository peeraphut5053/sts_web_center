<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
require_once "../initial.php";
$CallModel = new CallModel();
$CallModel->SyteLine_Models();
$Data = new BcTag();
$Data->setConn($ConnSL);

if ($load == "SearchTagTraceLot") {
    $job = isset($_POST["job"]) ? $_POST["job"] : (isset($_GET["job"]) ? $_GET["job"] : "");
    $item = isset($_POST["item"]) ? $_POST["item"] : (isset($_GET["item"]) ? $_GET["item"] : "");
    $rs = $Data->TagTraceLot($job, $item);
    echo json_encode($rs);
}
