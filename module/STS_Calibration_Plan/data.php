<?php

header("Access-Control-Allow-Origin: *");

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

include "../../initial.php";
require_once __DIR__ . "/../../include/class.SeaweedFS.php";

if ($load == "SaveCalibrationPlan") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $QcTestLab = new QcTestLab();
    $QcTestLab->setConn($ConnSL);
    $rs = $QcTestLab->SaveCalibrationPlan( $measuring, $code_no, $s_n, $manufacturer, $model, $range, $range_cal, $acceptance, $due_date, $actual_cal, $next_cal, $company, $frequency, $loc_equipment, $onlyexternal);
    echo json_encode($rs);
} else if ($load == "GetData") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $QcTestLab = new QcTestLab();
    $QcTestLab->setConn($ConnSL);
    $rs = $QcTestLab->GetCalibrationPlan($StartDate, $EndDate, $ReportNo, $codeno);
    echo json_encode($rs);
} else if ($load == "UpdateCalibration") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $QcTestLab = new QcTestLab();
    $QcTestLab->setConn($ConnSL);
    $rs = $QcTestLab->UpdateCalibrationPlan( $measuring, $code_no, $s_n, $manufacturer, $model, $range, $range_cal, $acceptance, $due_date, $actual_cal, $next_cal, $company, $frequency, $loc_equipment, $onlyexternal, $old_onlyexternal);
    echo json_encode($rs);
} else if ($load == "DeleteCalibrationPlan") {
    // Read the stored attachment name rather than accepting a client file path.
    $stmt = sqlsrv_query($ConnSL,
        'SELECT Doc FROM STS_QA_LAB_CalPlan WHERE RptNo = ?', array($RptNo));
    if ($stmt === false) {
        http_response_code(500);
        echo json_encode(false);
        exit;
    }
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($stmt);
    $deleted = sqlsrv_query($ConnSL,
        'DELETE FROM STS_QA_LAB_CalPlan WHERE RptNo = ?', array($RptNo));
    if ($deleted === false) {
        http_response_code(500);
        echo json_encode(false);
        exit;
    }
    sqlsrv_free_stmt($deleted);
    if (!empty($row['Doc'])) {
        $filename = basename(str_replace('\\', '/', $row['Doc']));
        SeaweedFS::delete('Calibration-Plan', $filename);
    }
    echo json_encode(array());
}
