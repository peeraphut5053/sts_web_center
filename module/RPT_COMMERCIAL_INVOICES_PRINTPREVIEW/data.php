<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
require_once "../initial.php";

if ($load == "ajax") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans->_start_Jobdate = $txtFromDate;
    $Trans->_end_Jobdate = $txtToDate;
    $Trans->_ref_num = $txtref_num;
    $Trans->_item = $txtItem;
    $Trans->_w_c = $txtw_c;
    $Trans = $Trans->GetJobSlit();

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

