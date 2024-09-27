<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
require_once "../initial.php";

if ($load == "Material_Transachtions_by_PO") {
    
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $INVEN = new Inventory();
    $INVEN->setConn($ConnSL);
    $INVENS = $INVEN->Material_Transachtions_by_PO($txtToDate, $txtFromDate, $grn_num, $voucher_num, $po_num, $item, $uf_lot);
    $CM = null;
    $INVEN = null;
    echo json_encode($INVENS);
} 


