<?php

header("Access-Control-Allow-Origin: *");
while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
require_once "../initial.php";
$CallModel = new CallModel();
$CallModel->SyteLine_Models();
$CallModelObj = new BcTag();
$CallModelObj->setConn($ConnSL);
if ($load == "V_WebApp_BarcodePrint") {
    $mv_bc_tag = $CallModelObj->V_WebApp_BarcodePrint($tag_id);
    echo json_encode($mv_bc_tag);
}
