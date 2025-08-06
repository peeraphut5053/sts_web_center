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
    $Data = $Data->GetCustomerSeq($cust_num);
    echo json_encode($Data);
}

if ($load == "SaveDeliveryTemp") {
    $arr_item = json_decode($item);
    $arr_act_weight = json_decode($act_weight);
    $arr_qty = json_decode($qty);
    $arr_u_m = json_decode($u_m);
    $Data = $Data->CreateDeliveryTemp($cust_num, $cust_seq, $delivery_date, $cust_po, $remark, $inv_num, $car, $ref_no, $arr_item, $arr_qty, $arr_u_m, $arr_act_weight, $pick_address);
    echo json_encode($Data);
}

if ($load == "UpdateDeliveryTemp") {
    $Data = $Data->UpdateDeliveryTemp($doc_num,$cust_num, $cust_seq, $delivery_date, $cust_po, $remark, $inv_num, $car, $ref_no, $item, $qty, $u_m, $act_weight, $pick_address, $item_old);
    echo json_encode($Data);
}


if ($load == "GetData") {
    $Data = $Data->GetReportDeliveryTemp($doc_num, $StartDate, $EndDate, $name);
    echo json_encode($Data);
}

if ($load == "GetItem") {
    $Data = $Data->GetItem();
    echo json_encode($Data);
}

if ($load == "GetNominee") {
    $Data = $Data->GetNominee($nominee);
    echo json_encode($Data);
}

if ($load == "DeleteDeliveryTempItem") {
    $Data = $Data->DeleteDeliveryTempItem($doc_num, $item);
    echo json_encode($Data);
}

if ($load == "AddItem") {
    $Data = $Data->AddDeliveryTempItem($doc_num, $item, $qty, $u_m, $act_weight);
    echo json_encode($Data);
}