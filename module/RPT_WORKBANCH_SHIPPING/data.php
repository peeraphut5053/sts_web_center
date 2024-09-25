




<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}
require_once "../initial.php";

if ($load == "ajax") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans->_start_Jobdate = $txtFromDate;
    $Trans->_end_Jobdate = $txtToDate;
    $Trans->_lot = $txtLot;
    $Trans->_ref_num = $txtref_num;
    $Trans->_trans_type = $txttrans_type;
    $Trans->_u_m = $txtu_m;

    $Trans = $Trans->GetJobOrrderProcessing();

    echo json_encode($Trans);
} else if ($load == "GetUMList") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $UM = new JOBORDER();
    $UM->setConn($ConnSL);
    $UMS = $UM->GetUMList();
    echo json_encode($UMS);
} else if ($load == "GetTranstypeList") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $UM = new JOBORDER();
    $UM->setConn($ConnSL);
    $UMS = $UM->GetTranstypeList();
    echo json_encode($UMS);
}


if ($load == "chart_donut") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans->_start_Jobdate = $txtFromDate;
    $Trans->_end_Jobdate = $txtToDate;
    $Trans->_lot = $txtLot;
    $Trans->_ref_num = $txtref_num;
    $Trans->_trans_type = $txttrans_type;

    $Trans = $Trans->GetDonutChart();

    echo json_encode($Trans);
} 

