<?php

header("Access-Control-Allow-Origin: *");

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

include "../../initial.php";

if ($load == "SaveCalibrationPlan") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $QcTestLab = new QcTestLab();
    $QcTestLab->setConn($ConnSL);
    $rs = $QcTestLab->SaveCalibrationPlan( $measuring, $code_no, $s_n, $manufacturer, $model, $range, $range_cal, $acceptance, $cal_date, $due_date, $actual_cal, $next_cal, $external, $internal, $frequency, $loc_equipment, $onlyexternal);
    echo json_encode($rs);
} else if ($load == "GetData") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $QcTestLab = new QcTestLab();
    $QcTestLab->setConn($ConnSL);
    $rs = $QcTestLab->GetCalibrationPlan();
    echo json_encode($rs);
} else if ($load == "UpdateCalibration") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $QcTestLab = new QcTestLab();
    $QcTestLab->setConn($ConnSL);
    $rs = $QcTestLab->UpdateCalibrationPlan( $measuring, $code_no, $s_n, $manufacturer, $model, $range, $range_cal, $acceptance, $cal_date, $due_date, $actual_cal, $next_cal, $external, $internal, $frequency, $loc_equipment, $onlyexternal);
    echo json_encode($rs);
} 
