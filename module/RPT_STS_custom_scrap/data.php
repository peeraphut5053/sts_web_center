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
    $rs = $STS_Custom->GetSTS_Custom_scrap($StartDate, $EndDate);
    echo json_encode($rs);
}

if ($load == "InsertCustomScrap") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $STS_Custom = new STS_Custom();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->AddSTS_Custom_Scrap($doc_no, $date, $item, $weight_KG, $value_scrap, $stamp_no, $ref_doc_no);
    echo json_encode($rs);
}

if ($load == "update_sts_custom_scrap") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $STS_Custom = new STS_Custom();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->UpdateSTS_Custom_Scrap($doc_no, $field, $newValue);
    echo json_encode($rs);
}