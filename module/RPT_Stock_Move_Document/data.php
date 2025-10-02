<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

include "../../initial.php";
$CallModel = new CallModel();
$CallModel->SyteLine_Models();
$Data = new Inventory();
$Data->setConn($ConnSL);

if ($load == "GetReportStockMove") {
    $Data = $Data->GetReportStockMove($item, $txtStartDate, $txtEndDate, $ThVendInvNum);
    echo json_encode($Data);
}