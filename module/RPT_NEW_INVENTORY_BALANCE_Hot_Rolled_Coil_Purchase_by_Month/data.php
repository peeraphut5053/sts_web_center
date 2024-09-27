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
    $INVENS = $INVEN->RPT_NEW_INVENTORY_BALANCE_Hot_Rolled_Coil_Purchase_by_Month($txtFromDate,$txtToDate);
    $CM = null;
    $INVEN = null;

    echo json_encode($INVENS);
}
