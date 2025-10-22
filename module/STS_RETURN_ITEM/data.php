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

$STS_Custom = new DeliveryOrder();
$STS_Custom->setConn($ConnSL);

if ($load == "GetDo") {
    $rs = $STS_Custom->GetDoNumReturn();
    echo json_encode($rs);
}

if ($load == "GetDoBySearch") {
    $rs = $STS_Custom->GetDoBySearch($search);
    echo json_encode($rs);
}

if ($load == "GetCoByDo") {
    $rs = $STS_Custom->GetCoByDo($do_num);
    echo json_encode($rs);
}

if ($load == "GetItemByCo") {
    $rs = $STS_Custom->GetItemByCo($do_num, $co_num);
    echo json_encode($rs);
}

if ($load == "GetReasonCause") {
    $rs = $STS_Custom->GetReasonCause();
    echo json_encode($rs);
}

if ($load == "GetIssueReturn") {
    $rs = $STS_Custom->GetIssueReturn();
    echo json_encode($rs);
}

if ($load == "GetDocNoReturn") {
    $rs = $STS_Custom->GetDocNoReturn();
    echo json_encode($rs);
}

if ($load == "GetItemReturnByDocNo") {
    $rs = $STS_Custom->GetItemReturnByDocNo($doc_no);
    echo json_encode($rs);
}

if ($load == "CreateItemReturn") {
    $arr_do_num = json_decode($do_num);
    $arr_co_num = json_decode($co_num);
    $arr_item = json_decode($item);
    $arr_qty = json_decode($qty);
    $arr_cause = json_decode($cause);
    $arr_issue = json_decode($issue);
    $arr_remark = json_decode($remark);
    $rs = $STS_Custom->CreateItemReturn($user, $remark_h, $arr_do_num, $arr_co_num, $arr_item, $arr_qty, $arr_issue, $arr_remark, $stat, $return_type);
    echo json_encode($rs);
}

if ($load == "AddItemReturn") {
    $rs = $STS_Custom->AddItemReturn($doc_no, $do_num, $co_num, $item, $qty, $issue, $remark, $user);
    echo json_encode($rs);
    # code...
}

if ($load == "UpdateItemReturn") {
    $rs = $STS_Custom->UpdateItemReturn($doc_no, $do_num, $do_num_old, $co_num, $co_num_old, $item, $item_old, $qty, $remark, $issue);
    echo json_encode($rs);
    # code...
}

if ($load == "DeleteItemReturn") {
    $rs = $STS_Custom->DeleteItemReturn($doc_no, $do_num, $co_num, $item);
    echo json_encode($rs);
    # code...
}

if ($load == "UpdateReturnHeader") {
    $rs = $STS_Custom->UpdateReturnHeader($doc_no, $remark, $stat, $return_type);
    echo json_encode($rs);
    # code...
}

if ($load == "ReceiveItem") {
    $rs = $STS_Custom->ReceiveItemReturn($doc_no,$returned);
    echo json_encode($rs);
    # code...
}

if ($load == "ApproveQc") {
    $rs = $STS_Custom->ApproveReturnByQc($doc_no , $qc);
    echo json_encode($rs);
    # code...
}

if ($load == "GetReturnPicByDocNo") {
    $rs = $STS_Custom->GetReturnPicByDocNo($doc_no);
    echo json_encode($rs);
    # code...
}

if ($load == "UpdateCause") {
    $rs = $STS_Custom->UpdateCauseReturn($doc_no, $do_num, $co_num, $item, $cause);
    echo json_encode($rs);
    # code...
}

if ($load == "UpdateStatus") {
    $rs = $STS_Custom->UpdateStatusReturn($doc_no, $do_num, $co_num, $item, $status);
    echo json_encode($rs);
    # code...
}

if ($load == "UpdateRemarkQc") {
    $rs = $STS_Custom->UpdateRemarkQcReturn($doc_no, $do_num, $co_num, $item, $remark);
    echo json_encode($rs);
    # code...
}

if ($load == "GetReportReturn") {
    $rs = $STS_Custom->GetReportReturn($StartDate, $EndDate, $doc_no);
    echo json_encode($rs);
    # code...
}

if ($load == "SalesApprove") {
    $rs = $STS_Custom->SalesApproveReturn($doc_no, $sale);
    echo json_encode($rs);
    # code...
}