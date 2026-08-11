<?php
header("Access-Control-Allow-Origin: *");
foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

require_once "../initial.php";

if ($load == "sum") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $BcTag = new BcTag();
    $BcTag->setConn($ConnSL);
    $rs = $BcTag->GetQcDataAnalysisSummary($StartDate, $EndDate);
    echo json_encode($rs);
}

else if ($load == "top5_stations") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $BcTag = new BcTag();
    $BcTag->setConn($ConnSL);
    $rs = $BcTag->GetQcTop5Stations($StartDate, $EndDate, isset($QcLoc) ? $QcLoc : 'P');
    echo json_encode($rs);
}

else if ($load == "customer_by_location") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $BcTag = new BcTag();
    $BcTag->setConn($ConnSL);
    $rs = $BcTag->GetQcDataAnalysisSummaryGroupByLoc($StartDate, $EndDate, isset($main_cause) ? $main_cause : '');
    echo json_encode($rs);
}

else if ($load == "monthly_nc_by_loc") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $BcTag = new BcTag();
    $BcTag->setConn($ConnSL);
    $rs = $BcTag->GetQcMonthlyNcByLoc($StartDate, $EndDate);
    echo json_encode($rs);
}

else if ($load == "all_nc_data") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $BcTag = new BcTag();
    $BcTag->setConn($ConnSL);
    $rs = $BcTag->GetQcAllNcData($StartDate, $EndDate);
    echo json_encode($rs);
}

else if ($load == "raw_mat_report") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $BcTag = new BcTag();
    $BcTag->setConn($ConnSL);
    $rs = $BcTag->GetQcRawMatReport($StartDate, $EndDate);
    echo json_encode($rs);
}

else if ($load == "process_detail") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $BcTag = new BcTag();
    $BcTag->setConn($ConnSL);
    $Process = isset($Process) ? $Process : (isset($process) ? $process : '');
    $QC_loc = isset($QC_loc) ? $QC_loc : (isset($qc_loc) ? $qc_loc : '');
    $rs = $BcTag->GetQcProcessDetail($StartDate, $EndDate, $Process, $QC_loc);
    echo json_encode($rs);
}

else if ($load == "qc_inspection_mistake") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $BcTag = new BcTag();
    $BcTag->setConn($ConnSL);
    $rs = $BcTag->GetQcInspectionMistake($StartDate, $EndDate, isset($QcLoc) ? $QcLoc : 'P');
    echo json_encode($rs);
}

else {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $BcTag = new BcTag();
    $BcTag->setConn($ConnSL);
    $rs = $BcTag->GetQcDataAnalysisSummaryGroup($StartDate, $EndDate,$load);
    echo json_encode($rs);
}