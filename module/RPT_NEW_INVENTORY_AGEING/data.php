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
    $INVENS = $INVEN->RPT_NEW_INVENTORY_AGEING($txtToDate,$txtFromDate,$product_code,$item,$rank_1,$rank_2,$rank_3,$rank_4,$rank_5);
    $CM = null;
    $INVEN = null;
    echo json_encode($INVENS);
}else if($load == "product_code"){
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $INVEN = new Inventory();
    $INVEN->setConn($ConnSL);
    $INVENS = $INVEN->product_code();
    $CM = null;
    $INVEN = null;
    echo json_encode($INVENS);
}

