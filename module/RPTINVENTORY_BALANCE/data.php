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
    $INVEN->_txtItem = $txtItem;
    $INVEN->_txtStartDate = $txtStartDate ;
    $INVEN->_txtEndDate = $txtEndDate;
    $INVENS = $INVEN->GetRows_SP();
    $CM = null;
    $INVEN = null;

    echo json_encode($INVENS);
}
