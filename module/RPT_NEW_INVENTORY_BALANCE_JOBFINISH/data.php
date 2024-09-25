<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

include "../../initial.php";

if ($load == "detail") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $INVEN = new Inventory();
    $INVEN->setConn($ConnSL);
    $INVENS = $INVEN->GetRows_NewInventoryBalanceReport_JobFinsh($FirstSearch,$Item, $txtStartDate, $txtEndDate,$TrandesctionSelect);
    $CM = null;
    $INVEN = null;

    echo json_encode($INVENS);
} else if ($load == "TrandesctionSelect") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $INVEN = new Inventory();
    $INVEN->setConn($ConnSL);
    $TranSelect = $INVEN->TrandesctionSelect();
    $CM = null;
    $INVEN = null;
    echo json_encode($TranSelect);
}

