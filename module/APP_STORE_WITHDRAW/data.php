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

if ($load == "GetDocListByApprove") {
    $rs = $STS_Custom->GetDocListByApprove();
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

if ($load == "GetWcDest") {
    $rs = $STS_Custom->GetWcDest();
    echo json_encode($rs);
}

if ($load == "GetItem") {
    $rs = $STS_Custom->GetItemList();
    echo json_encode($rs);
}

if ($load == "GetReason") {
    $rs = $STS_Custom->GetReasonCode();
    echo json_encode($rs);
}

if ($load == "CreateWithdraw") {
    $arr_item = json_decode($item);
    $arr_qty = json_decode($qty);
    $arr_wc_dest = json_decode($wc_dest);
    $arr_remark = json_decode($remark);
    $rs = $STS_Custom->CreateWithdraw($dept,  $user, $remark_h, $arr_item, $arr_qty, $arr_wc_dest, $arr_remark);
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

if ($load == "CancelApproveTwoWithdraw") {
    $rs = $STS_Custom->CancelApproveTwoWithdraw($doc_no, $approve);
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
    $rs = $STS_Custom->UpdateWithdrawItem($doc_no,$line_id, $item, $qty, $wc_dest,  $remark);
    echo json_encode($rs);
}

if ($load == "SaveMiscIssue") {
    $rs = $STS_Custom->SaveMiscIssue($doc_no,$item, $loc, $reason, $qty, $user, $line_id, $acct, $acct_unit1, $um);
    echo json_encode($rs);
}

if ($load == "SaveMiscIssueByLot") {
    $rs = $STS_Custom->SaveMiscIssueByLot($doc_no,$item, $lot, $qty, $fromLoc, $toLoc, $user, $line_id);
    echo json_encode($rs);
}

if ($load == "UpdateQty_rcvd") {
    $rs = $STS_Custom->UpdateQty_rcvd($doc_no, $item, $line_id, $val );
    echo json_encode($rs);
}

if ($load == "UpdateQty_return") {
    $rs = $STS_Custom->UpdateQty_return($doc_no, $item, $line_id, $val);
    echo json_encode($rs);
}

if ($load == "GetLot") {
    $rs = $STS_Custom->GetLot($item);
    echo json_encode($rs);
}

if ($load == "GetFromLoc") {
    $rs = $STS_Custom->GetFromLoc($item, $lot);
    echo json_encode($rs);
}

if ($load == "GetToLoc") {
    $rs = $STS_Custom->GetToLoc($item);
    echo json_encode($rs);
}

if ($load == "GetToLocLot") {
    $rs = $STS_Custom->GetToLocLot($wc);
    echo json_encode($rs);
}

if ($load == "GetLocItem") {
    $rs = $STS_Custom->GetLocItem($item);
    echo json_encode($rs);
}

if ($load == "UpdateWithdrawHeader") {
    $rs = $STS_Custom->UpdateWithdrawHeader($doc_no, $dept, $remark);
    echo json_encode($rs);
} 

if ($load == "GetUM") {
    $rs = $STS_Custom->GetUM($item);
    echo json_encode($rs);
}

if ($load == "CheckExistLoc") {
    $rs = $STS_Custom->CheckExistLoc($item, $loc);
    echo json_encode($rs);
}

if ($load == "CheckStockComplete") {
    $rs = $STS_Custom->CheckStockComplete($doc_no, $item);
    echo json_encode($rs);
}

if ($load == "GetReportWithdraw") {
    $rs = $STS_Custom->GetReportWithdraw($doc_no, $StartDate, $EndDate, $dept, $userApprove, $approve1,$approve2,$stock, $wc);
    echo json_encode($rs);
}

if ($load == 'DeleteItemWithdraw') {
    $rs = $STS_Custom->DeleteItemWithdraw($doc_no, $line_id);
    echo json_encode($rs);
}