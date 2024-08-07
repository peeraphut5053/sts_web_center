<?php

header("Access-Control-Allow-Origin: *");


while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
require_once "../initial.php";


if ($load == "ajax") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans->_start_date = $txtFromDate;
    $Trans->_end_date = $txtToDate;
    $Trans->_ref_num = $txtref_num;
    $Trans->_item = $txtItem;
    $Trans->_w_c = $txtw_c;
    $Trans = $Trans->GetJobPacking();

    echo json_encode($Trans);
}
if ($load == "ajax2") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans->_start_date = $txtFromDate;
    $Trans->_end_date = $txtToDate;
    $Trans->_ref_num = $txtref_num;
    $Trans->_item = $txtItem;
    $Trans->_w_c = $txtw_c;
    $Trans = $Trans->GetJobPacking_no_job($txtItem, $txtref_num, $txtw_c, $wc_group_query);
//    $Trans = $Trans->GetJobPacking2($txtItem, $txtref_num, $txtw_c, $wc_group_query);

//    if (count($Trans) == 0) {
//        echo count($Trans);
//        $Trans = $Trans->GetJobPacking_no_job($txtItem, $txtref_num, $txtw_c, $wc_group_query);
//    }

    echo json_encode($Trans);
}

if ($load == "STS_JOB_REPORT_DIARY_SUB_QUERY") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans->_start_date = $txtFromDate;
    $Trans->_end_date = $txtToDate;
    $Trans->_ref_num = $txtref_num;
    $Trans->_item = $txtItem;
    $Trans->_w_c = $txtw_c;
    $Trans = $Trans->STS_JOB_REPORT_DIARY_SUB_QUERY($txtItem, $txtref_num, $txtw_c, $wc_group_query);
    echo json_encode($Trans);
}

if ($load == "StampingReport") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans->_start_date = $txtFromDate;
    $Trans->_end_date = $txtToDate;
    $Trans->_ref_num = $txtref_num;
    $Trans->_item = $txtItem;
    $Trans->_w_c = $txtw_c;
    $Trans = $Trans->StampingReport($txtItem, $txtref_num, $txtw_c, $wc_group_query);

    echo json_encode($Trans);
}

//if ($load == "StampingReport") {
//    
//    
//    $CallModel = new CallModel();
//    $CallModel->SyteLine_Models();
//
//    $Trans = new JobOrder();
//    $Trans->setConn($ConnSL);
//    $Trans->_start_date = $txtFromDate;
//    $Trans->_end_date = $txtToDate;
//    $Trans->_ref_num = $txtref_num;
//    $Trans->_item = $txtItem;
//    $Trans->_w_c = $txtw_c;
//    $Trans = $Trans->StampingReport($txtItem, $txtref_num, $txtw_c, $wc_group_query);
//
//    echo json_encode($Trans);
//}
if ($load == "STS_QTY_MOVE_REPORT_header") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->STS_QTY_MOVE_REPORT_header($doc_num);

    echo json_encode($Trans);
}
if ($load == "STS_QTY_MOVE_REPORT") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->STS_QTY_MOVE_REPORT($doc_num);

    echo json_encode($Trans);
}
if ($load == "RPT_JOBPACKINGLOT") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans->_start_date = $txtFromDate;
    $Trans->_end_date = $txtToDate;
    $Trans->_ref_num = $txtref_num;
    $Trans->_item = $txtItem;
    $Trans->_w_c = $txtw_c;
    $Trans = $Trans->GetJobPacking();

    echo json_encode($Trans);
}

if ($load == "GetJobPackingDialy") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans->_start_date = $txtFromDate;
    $Trans->_end_date = $txtToDate;
    $Trans->_ref_num = $txtref_num;
    $Trans->_item = $txtItem;
    $Trans->_w_c = $txtw_c;
    $Trans = $Trans->GetJobPackingDialy();

    echo json_encode($Trans);
}

if ($load == "ReportProductionDaily") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans->_start_date = $txtFromDate;
    $Trans->_end_date = $txtToDate;
    $Trans->_ref_num = $txtref_num;
    $Trans->_item = $txtItem;
    $Trans->_w_c = $txtw_c;
    $Trans = $Trans->ReportProductionDaily();

    echo json_encode($Trans);
}

if ($load == "ReportFormingDaily") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans->_start_date = $txtFromDate;
    $Trans->_end_date = $txtToDate;
    $Trans->_ref_num = $txtref_num;
    $Trans->_item = $txtItem;
    $Trans->_w_c = $txtw_c;
    $Trans = $Trans->ReportFormingDaily();

    echo json_encode($Trans);
}




if ($load == "chart_donut") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans->_start_Jobdate = $txtFromDate;
    $Trans->_end_Jobdate = $txtToDate;
    $Trans->_ref_num = $txtref_num;

    $Trans = $Trans->GetDonutChart();

    echo json_encode($Trans);
}

if ($load == "updateRemark") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->updateRemark($job, $remark);
    echo json_encode($Trans);
}


if ($load == "workcenter") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->workcenter();
    echo json_encode($Trans);
}

if ($load == "workcenter_selection") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->workcenter_selection();
    echo json_encode($Trans);
}

if ($load == "location") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->location();
    echo json_encode($Trans);
}

if ($load == "locationCL") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->locationCL();
    echo json_encode($Trans);
}

if ($load == "LocationByDo") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->LocationByDo($do);
    echo json_encode($Trans);
}

if ($load == "locationPTR") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->locationPTR();
    echo json_encode($Trans);
}

if ($load == "dataMatl") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->dataMatl($txtFromDate, $txtToDate, $txtItem, $txtref_num, $txtw_c);
    echo json_encode($Trans);
}




if ($load == "SelectForming") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->SelectForming($startdate, $enddate, $item, $refnum, $w_c);
    echo json_encode($Trans);
}

//-------------------------------- Start CRUD STS_forming_reason_description -----------------------------
if ($load == "SelectForming_reason_description") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->SelectForming_reason_description();
    echo json_encode($Trans);
}


if ($load == "CreateForming_reason_description") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->CreateForming_reason_description($reason_description);
    echo json_encode($Trans);
}

if ($load == "UpdateForming_reason_description") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->UpdateForming_reason_description($id, $reason_description);
    echo json_encode($Trans);
}

if ($load == "DeleteForming_reason_description") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->DeleteForming_reason_description($id, $reason_description);
    echo json_encode($Trans);
}

//-------------------------------- End CRUD STS_forming_reason_description -----------------------------
////-------------------------------- Start CRUD STS_forming_reason_description detail -----------------------------
if ($load == "SelectForming_reason_description_detail") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->SelectForming_reason_description_detail($reason_id);
    echo json_encode($Trans);
}


if ($load == "CreateForming_reason_description_detail") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->CreateForming_reason_description_detail($reason_description);
    echo json_encode($Trans);
}

if ($load == "UpdateForming_reason_description_detail") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->UpdateForming_reason_description_detail($id, $reason_description);
    echo json_encode($Trans);
}

if ($load == "DeleteForming_reason_description_detail") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->DeleteForming_reason_description_detail($id, $reason_description);
    echo json_encode($Trans);
}

//-------------------------------- End CRUD STS_forming_reason_description detail -----------------------------
//-------------------------------- Start CRUD STS_forming_reason -----------------------------
if ($load == "SelectFormingModal") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->SelectForming($txtFromDate, $txtToDate, $txtItem, $txtref_num, $txtw_c);
    echo json_encode($Trans);
}

if ($load == "CreateForming") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->CreateForming($reason_id, $reason_detail_id, $time_stopped, $time_used, $w_c, $remark, $times_count);
    echo json_encode($Trans);
}

if ($load == "UpdateForming") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->UpdateForming($id, $reason_id, $time_stopped, $time_used, $w_c);
    echo json_encode($Trans);
}

if ($load == "DeleteForming") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->DeleteForming($id, $reason_id, $time_stopped, $time_used, $create_date, $w_c);
    echo json_encode($Trans);
}

//-------------------------------- End CRUD STS_forming_reason -----------------------------
//-------------------------------- Start CRUD STS_forming_reason -----------------------------
if ($load == "SelectFormingModal_reason_meter") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->SelectForming_reason_meter($meters_start, $meters_end, $w_c);
    echo json_encode($Trans);
}

if ($load == "CreateForming_reason_meter") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->CreateForming_reason_meter($meters_start, $meters_end, $time_save, $w_c);
    echo json_encode($Trans);
}

if ($load == "UpdateForming_reason_meter") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->UpdateForming_reason_meter($id, $meters_minute, $meters_start, $meters_end, $time_save, $w_c);
    echo json_encode($Trans);
}

if ($load == "DeleteForming_reason_meter") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->DeleteForming_reason_meter($id, $meters_minute, $meters_start, $meters_end, $time_save, $w_c);
    echo json_encode($Trans);
}

if ($load == "getForming_last_meter") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->getForming_last_meter($w_c);
    echo json_encode($Trans);
}

//-------------------------------- End CRUD STS_forming_reason -----------------------------
// RadioOT
if ($load == "CreateBreakTimeForming") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->CreateBreakTimeForming($w_c, $startdate, $enddate, $rate);
    echo json_encode($Trans);
}

if ($load == "SelectBreakTimeForming") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->SelectBreakTimeForming($meters_start, $meters_end, $w_c);
    echo json_encode($Trans);
}


if ($load == "saveOperation") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->saveOperation($txtFromDate, $txtToDate, $operationWeight, $operationSpeed, $operationTime, $w_c);
    echo json_encode($Trans);
}

if ($load == "BoatNoteSelectByDoGroup") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->BoatNoteSelectByDoGroup($do_group_name,$loc,$boatPosition);

    echo json_encode($Trans);
}

if ($load == "BoatNoteSummaryByDoGroup") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->BoatNoteSummaryByDoGroup($do_group_name,$loc);
    echo json_encode($Trans);
}

if ($load == "ReportTagBoatNote") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->ReportTagBoatNote();
    echo json_encode($Trans);
}

if ($load == "getFormingOperation") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->getFormingOperation($txtFromDate,$txtToDate,$txtw_c);
    echo json_encode($Trans);
}

if ($load == "FinishingReason") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->SelectFinishingReason();
    echo json_encode($Trans);
}

if ($load == "AddNewReasonFinishing") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->SelectFinishingReason();
    echo json_encode($Trans);
}

if ($load == "SelectFinishing") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->SelectFinishing($startdate, $enddate, $txtw_c);
    echo json_encode($Trans);
}




