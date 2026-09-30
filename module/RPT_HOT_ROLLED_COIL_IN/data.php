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

if ($load == 'ajax' || $load == 'pipe_in') {
    // รายงานท่อขาเข้า - STS_custom_OUTsp (ภาพที่ 1)
    $data = $STS_Custom->GetCustomOutSpReport($txtFromDate, $txtToDate, $doc_num);
    echo json_encode($data);
} else if ($load == 'custom_in') {
    // V_STS_custom_IN
    $data = $STS_Custom->GetCustomInReport($txtFromDate, $txtToDate, $sno);
    echo json_encode($data);
}
