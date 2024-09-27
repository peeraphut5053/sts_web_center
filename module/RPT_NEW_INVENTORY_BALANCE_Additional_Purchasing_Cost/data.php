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
    $txtToReference_No = "";
    $INVENS = $INVEN->RPT_NEW_INVENTORY_BALANCE_Additional_Purchasing_Cost($txtFromDate,$txtToDate,$txtFromReference_No,$txtToReference_No );
    $CM = null;
    $INVEN = null;

    echo json_encode($INVENS);
}
