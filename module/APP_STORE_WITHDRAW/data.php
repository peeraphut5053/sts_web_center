<?php

header("Access-Control-Allow-Origin: *");

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

include "../../initial.php";
$CallModel = new CallModel();
$CallModel->SyteLine_Models();

$STS_Custom = new Buyer();
$STS_Custom->setConn($ConnSL);

if ($load == "GetDocList") {
    $rs = $STS_Custom->GetDocList();
    echo json_encode($rs);
}

if ($load == "GetWithdrawByDocNo") {
    $rs = $STS_Custom->GetWithdrawByDocNo($doc_no);
    echo json_encode($rs);
}

if ($load == "GetDept") {
    $rs = $STS_Custom->GetDept();
    echo json_encode($rs);
}

if ($load == "GetItem") {
    $rs = $STS_Custom->GetItemList();
    echo json_encode($rs);
}

if ($load == "CreateWithdraw") {
    $arr_item = json_decode($item);
    $arr_qty = json_decode($qty);
    $arr_wc_dest = json_decode($wc_dest);
    $arr_remark = json_decode($remark);
    $rs = $STS_Custom->CreateWithdraw($dept, $wc, $user, $remark_h, $arr_item, $arr_qty, $arr_wc_dest, $arr_remark);
    echo json_encode($rs);
}

if ($load == "UpdateWithdraw") {
    $arr_item = json_decode($item);
    $arr_qty = json_decode($qty);
    $arr_qty_rcvd = json_decode($qty_rcvd);
    $arr_wc_dest = json_decode($wc_dest);
    $arr_remark = json_decode($remark);
    $rs = $STS_Custom->UpdateWithdraw($doc_no,$dept, $wc, $user, $remark_h, $arr_item, $arr_qty,$arr_qty_rcvd, $arr_wc_dest, $arr_remark);
    echo json_encode($rs);
}

if ($load == "ApproveOneWithdraw") {
    $rs = $STS_Custom->ApproveOneWithdraw($doc_no, $approve);
    echo json_encode($rs);
}

if ($load == "ApproveTwoWithdraw") {
    $rs = $STS_Custom->ApproveTwoWithdraw($doc_no, $approve);
    echo json_encode($rs);
}

if ($load == "ApproveStockWithdraw") {
    $rs = $STS_Custom->ApproveStockWithdraw($doc_no, $stock);
    echo json_encode($rs);
}

if ($load == "ApproveUserWithdraw") {
    $rs = $STS_Custom->ApproveUserWithdraw($doc_no);
    echo json_encode($rs);
}

if ($load == "AddNewItemWithdraw") {
    $rs = $STS_Custom->AddNewItemWithdraw($doc_no, $item, $qty, $wc_dest, $remark);
    echo json_encode($rs);
}

if ($load == "UpdateWithdrawItem") {
    $rs = $STS_Custom->UpdateWithdrawItem($doc_no, $item_old, $item, $qty, $wc_dest, $wc_dest_old, $remark);
    echo json_encode($rs);
}