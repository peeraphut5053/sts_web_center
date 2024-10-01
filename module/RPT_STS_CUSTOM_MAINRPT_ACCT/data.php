<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
require_once "../initial.php";

if ($load == "ajax") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $STS_Custom = new STS_Custom();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->GetSTS_custom_mainrpt_acct($StartDate, $EndDate);
    echo json_encode($rs);
}

if ($load == "reportPDF") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $STS_Custom = new STS_Custom();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->GetDataReportRemainPDF($StartDate, $EndDate);
    echo json_encode($rs);
}

if ($load == "reportMovingPDF") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $STS_Custom = new STS_Custom();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->GetDataReportMoving($StartDate, $EndDate, $StartDate2, $EndDate2);
    echo json_encode($rs);
}

if ($load == "reportSummaryPDF") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $STS_Custom = new STS_Custom();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->GetDataReportSummary($StartDate, $EndDate, $StartDate2, $EndDate2);
    echo json_encode($rs);
}   