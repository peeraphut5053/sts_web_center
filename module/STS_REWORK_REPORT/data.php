<?php

header("Access-Control-Allow-Origin: *");

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    if (is_array($value)) {
        // ถ้าเป็น array ให้วนลูปอีกครั้งเพื่อ trim แต่ละ element
        foreach ($value as $subKey => $subValue) {
            $$key[$subKey] = trim($subValue);
        }
    } else {
        $$key = trim($value);
    }
}

include "../../initial.php";
$CallModel = new CallModel();
$CallModel->SyteLine_Models();
$STS_Custom = new Production();

if ($load == "GetReport") {
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->GetReworkReport($doc_rework, $from_date, $to_date);
    echo json_encode($rs);
}

if ($load == "SaveStatus") {
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->SaveStatus($doc_no,  $seq);
    echo json_encode($rs);
}