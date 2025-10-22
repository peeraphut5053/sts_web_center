<?php

header("Access-Control-Allow-Origin: *");

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

include "../../initial.php";
$CallModel = new CallModel();
$CallModel->SyteLine_Models();

$STS_Custom = new PurchaseOrder();
$STS_Custom->setConn($ConnSL);

if ($load == "GetReportPurchaseBySupplier") {
    $rs = $STS_Custom->GetReportPurchaseBySupplier($supplier, $from_date, $to_date);
    echo json_encode($rs);
}

if ($load == "GetSupplierList") {
    $rs = $STS_Custom->GetSupplierList();
    echo json_encode($rs);
}

if ($load == "GetReportPurchaseByAll") {
    $rs = $STS_Custom->GetReportPurchaseByAll($from_date, $to_date);
    echo json_encode($rs);
}
