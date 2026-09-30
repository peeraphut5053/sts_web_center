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
$sno = isset($sno) ? $sno : '';
$doc_num = isset($doc_num) ? $doc_num : '';

if ($load == 'ajax' || $load == 'coil_out') {
    // รายงานเหล็กม้วนขาออก - V_STS_custom_IN (ภาพที่ 2)
    $data = $STS_Custom->GetCustomInReport($txtFromDate, $txtToDate, $sno);
    echo json_encode($data);
} else if ($load == 'custom_out_sp') {
    // STS_custom_OUTsp
    $data = $STS_Custom->GetCustomOutSpReport($txtFromDate, $txtToDate, $doc_num);
    echo json_encode($data);
}
