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

    $STS_Custom = new STS_Custom();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->GetSTS_Custom_In($StartDate, $EndDate);
    echo json_encode($rs);
}

if ($load == "insert_sts_custom_in") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $STS_Custom = new STS_Custom();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->AddSTS_Custom_In($doc_no, $date_in, $date_stock, $supplier, $country, $AD_rate, $weight_KG, $value_in, $remark, $item,  $type);
    echo json_encode($rs);
}

if ($load == "update_sts_custom_in") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $STS_Custom = new STS_Custom();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->UpdateSTS_Custom_In($doc_no, $field, $newValue);
    echo json_encode($rs);
}