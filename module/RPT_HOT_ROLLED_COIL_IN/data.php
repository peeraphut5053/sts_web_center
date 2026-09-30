<?php
header("Access-Control-Allow-Origin: *");

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

include "../../initial.php";

$load = isset($load) ? $load : '';

$CallModel = new CallModel();
$CallModel->SyteLine_Models();
$STS_Custom = new STS_Custom();
$STS_Custom->setConn($ConnSL);

$txtFromDate = isset($txtFromDate) ? $txtFromDate : '';
$txtToDate = isset($txtToDate) ? $txtToDate : '';
$doc_num = isset($doc_num) ? $doc_num : '';
$sno = isset($sno) ? $sno : '';

if ($load == 'ajax' || $load == 'pipe_in' || $load == 'custom_in') {
    // รายงานขาเข้า - V_STS_custom_IN (ค้นหาด้วย S_NO)
    $data = $STS_Custom->GetCustomInReport($txtFromDate, $txtToDate, $sno);
    echo json_encode($data);
} else if ($load == 'custom_out_sp') {
    // STS_custom_OUTsp
    $data = $STS_Custom->GetCustomOutSpReport($txtFromDate, $txtToDate, $doc_num);
    echo json_encode($data);
}
