<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "./initial.php";
//echo $conn;
$PO = new SLPO();
$PO->setConn($ConnSL);
if ($type == "") {
    $BuildRows = $PO->AjaxGetItemsDropdownWithCond(" po_num LIKE '%%'  ");
} else if ($type == "po") {
    $BuildRows = $PO->GetAvailablePO($vend_num, $po_num, $item);
    echo json_encode($BuildRows);
} else if ($type == "po_line") {
    $BuildRows = $PO->GetPoLine($po, $po_line);
    echo json_encode($BuildRows);
} else if ($type == "grn_hdr") {
    $BuildRows = $PO->GetAvailableGRN_HDR($vend_name);
    echo json_encode($BuildRows);
} else if ($type == "grn_barcode") {
    $BuildRows = $PO->GetAvailableGRN_Barcode($grn_barcode);
    echo json_encode($BuildRows);
} else if ($type == "po_barcode") {
    $BuildRows = $PO->GetAvailablePO_Barcode($grn_barcode, $po_barcode);
    echo json_encode($BuildRows);
} else if ($type == "get_sl_poitem_data") {
    $SLPOI = new SLPOI();
    $SLPOI->setConn($ConnSL);
    $SLPOI->_po_num = $po_num;
    $SLPOI->_po_line = $po_line;
    $SLPOI->_grn_num = $grn_num;
    $SLPOI->_lot = $lot;
    $SLPOI->GetPOData();

    $Result = array();
//    if ($SLPOI->item) {
    $Result["query"] = $SLPOI->query;
    $Result["item"] = $SLPOI->item;
    $Result["qty"] = $SLPOI->qty;
    $Result["u_m"] = $SLPOI->u_m;
    $Result["vend_num"] = $SLPOI->vend_num;
    $Result["vend_name"] = $SLPOI->vend_name;
    $Result["uf_grade"] = $SLPOI->uf_grade;
    $Result["grn_line"] = $SLPOI->grn_line;
    $Result["qty_shipped_conv"] = $SLPOI->qty_shipped_conv;
    $Result["grn_hdr_date"] = $SLPOI->grn_hdr_date->format("m/d/Y");
//    } else {
//        
//    }

    $SLPOI = null;
//    $BuildRows = $SLPOI->GetAvailableGRN_HDR();
    echo json_encode($Result);
}

sqlsrv_close($ConnSL);
