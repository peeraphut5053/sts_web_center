<?php

header("Access-Control-Allow-Origin: *");
while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
require_once "../initial.php";

$CallModel = new CallModel();
$CallModel->SyteLine_Models();
$AD_All_Temp = new Invoice();
$AD_All_Temp->setConn($ConnSL);

if ($load == "ajax") {
    $AD_All_Temp = $AD_All_Temp->Report_AD($inv_num,$StartDate, $EndDate);
    echo json_encode($AD_All_Temp);
}