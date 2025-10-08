<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../../initial.php";

if ($load == "ajax") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $INVEN = new Inventory();
    $INVEN->setConn($ConnSL);
    $INVENS = $INVEN->RPT_NEW_INVENTORY_BALANCE_Stock_Card_by_Invoice_No($item, $txtStartDate, $txtEndDate,$ThVendInvNum);
    $CM = null;
    $INVEN = null;
    echo json_encode($INVENS);
}

if ($load == "InsertStockCard") {
    $dataToSend = json_decode($dataToSend, true);
    $CM = new CallModel();
    $CM->SyteLine_Models();
    foreach ($dataToSend as $data) {
        $INVEN = new Inventory();
        $INVEN->setConn($ConnSL);
        $INVEN->Insert_Stock_Card_by_Invoice_No($data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6]);
    }
    echo json_encode(array("status" => "success", "message" => "Insert Stock Card Complete"));
}