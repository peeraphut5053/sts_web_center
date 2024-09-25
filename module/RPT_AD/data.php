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
$AD_All_Temp = new Invoice();
$AD_All_Temp->setConn($ConnSL);

if ($load == "ajax") {
    $AD_All_Temp = $AD_All_Temp->Report_AD($inv_num,$StartDate, $EndDate);
    echo json_encode($AD_All_Temp);
}