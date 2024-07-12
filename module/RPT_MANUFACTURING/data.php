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
    $Trans = $Trans->GetManufacturing();

    echo json_encode($Trans);
} 
if ($load == "selectWorkCenter") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->wc_mst();

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
    $Trans = $Trans->updateRemark($job,$remark);
    echo json_encode($Trans);
}


