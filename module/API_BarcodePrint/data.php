<?php

header("Access-Control-Allow-Origin: *");
foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
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
