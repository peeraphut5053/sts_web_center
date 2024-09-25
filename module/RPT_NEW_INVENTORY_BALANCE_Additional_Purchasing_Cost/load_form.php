<?php
foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}
include "../../initial.php";


    $CM = new CallModel();
    $CM->SyteLine_Models();
    $INVEN = new Inventory();
    $INVEN->setConn($ConnSL);
    //$INVEN->_txtItemStart = $txtItemStart;
    //$INVEN->_txtItemEnd = $txtItemEnd;
    $INVEN->_txtItemEnd = $item;
    $INVEN->_txtStartDate = $txtStartDate;
    $INVEN->_txtEndDate = $txtEndDate;
    $INVENS = $INVEN->GetRows_NewInventoryBalanceReport_Detail($item, $txtStartDate, $txtEndDate);
    $CM = null;
    $INVEN = null;

    echo json_encode($INVENS);

