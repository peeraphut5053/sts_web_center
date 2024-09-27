<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
require_once "../initial.php";

if ($load == "RPT_NEW_INVENTORY_Shipped_Date_Vs_Invoiced_Date") {
    
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $INVEN = new Inventory();
    $INVEN->setConn($ConnSL);
    $INVENS = $INVEN->RPT_NEW_INVENTORY_Shipped_Date_Vs_Invoiced_Date($txtFromDate, $txtToDate, $co_num, $do_num, $inv_num);
    $CM = null;
    $INVEN = null;
    echo json_encode($INVENS);
} 


