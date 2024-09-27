<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../../initial.php";

if ($load == "detail") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $INVEN = new Inventory();
    $INVEN->setConn($ConnSL);
    $INVENS = $INVEN->GetRows_NewInventoryBalanceReport_Detail_edit($FirstSearch,$Item, $txtStartDate, $txtEndDate,$TrandesctionSelect);
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

