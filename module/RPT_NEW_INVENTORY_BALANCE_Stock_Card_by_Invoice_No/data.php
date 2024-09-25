<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
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