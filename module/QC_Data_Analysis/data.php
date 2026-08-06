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
    $rs = $BcTag->GetQcTop5Stations($StartDate, $EndDate);
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

else {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $BcTag = new BcTag();
    $BcTag->setConn($ConnSL);
    $rs = $BcTag->GetQcDataAnalysisSummaryGroup($StartDate, $EndDate,$load);
    echo json_encode($rs);
}