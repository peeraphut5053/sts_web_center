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

if ($load == "InsertStockMove") {
    $dataToSend = json_decode($dataToSend, true);
    $CM = new CallModel();
    $CM->SyteLine_Models();
    foreach ($dataToSend as $data) {
        $INVEN = new Inventory();
        $INVEN->setConn($ConnSL);
        $INVEN->Insert_Stock_Move_by_Document_No($data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6]);
    }
    echo json_encode(array("status" => "success", "message" => "Insert Stock Move Complete"));
}