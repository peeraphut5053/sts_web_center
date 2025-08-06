<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

include "../../initial.php";
$CallModel = new CallModel();
$CallModel->SyteLine_Models();
$Data = new Costing();
$Data->setConn($ConnSL);

if ($load == "Get") {
    $Data = $Data->GetCustomer();
    echo json_encode($Data);
}

if ($load == "GetCustomerSeq") {
    $Data = $Data->GetCustomerSeqQuote($cust_num);
    echo json_encode($Data);
}

if ($load == "SaveQuote") {
    $arr_item = json_decode($item);
    $arr_qty = json_decode($qty);
    $arr_unit = json_decode($unit);
    $arr_weight = json_decode($weight_pcs);
    $arr_u_m = json_decode($u_m);
    $Data = $Data->CreateQuote($cust_num, $cust_seq,  $remark,$ref_no, $arr_item, $arr_qty, $arr_u_m, $arr_unit, $arr_weight, $cust_to,$tel,$subject,$remark2,$salesman);
    echo json_encode($Data);
}

if ($load == "UpdateQuote") {
    $Data = $Data->UpdateQuote($doc_num, $cust_num, $cust_seq, $remark, $ref_no, $item, $qty, $u_m, $unit_price, $item_old, $weight_pcs,$cust_to,$tel,$subject,$remark2,$revised,$salesman);
    echo json_encode($Data);
}


if ($load == "GetData") {
    $Data = $Data->GetReportQuote($doc_num, $StartDate, $EndDate, $name);
    echo json_encode($Data);
}

if ($load == "GetItem") {
    $Data = $Data->GetItemQuote($search);
    echo json_encode($Data);
}

if ($load == "GetNominee") {
    $Data = $Data->GetNominee($nominee);
    echo json_encode($Data);
}

if ($load == "DeleteQuoteItem") {
    $Data = $Data->DeleteQuoteItem($doc_num, $item);
    echo json_encode($Data);
}

if ($load == "AddItem") {
    $Data = $Data->AddQuoteItem($doc_num, $item, $qty, $u_m, $unit_price, $weight_pcs);
    echo json_encode($Data);
}