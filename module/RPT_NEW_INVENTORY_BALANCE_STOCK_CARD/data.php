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
    $INVENS = $INVEN->GetRows_NewInventoryBalanceReport_Detail($item, $txtStartDate, $txtEndDate);
    $CM = null;
    $INVEN = null;
    echo json_encode($INVENS);
}