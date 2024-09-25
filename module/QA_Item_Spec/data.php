<?php

header("Access-Control-Allow-Origin: *");

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

include "../../initial.php";

if ($load == "Spec") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $QcTestLab = new QcTestLab();
    $QcTestLab->setConn($ConnSL);
    $rs = $QcTestLab->GetQAItemSpec();
    echo json_encode($rs);
}

if ($load == "Save") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $QcTestLab = new QcTestLab();
    $QcTestLab->setConn($ConnSL);
    $rs = $QcTestLab->AddQAItemSpec($itemSpec, $spec, $Grade, $SCH, $remark, $specCert);
    echo json_encode($rs);
}

if ($load == "UpdateRemark") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $QcTestLab = new QcTestLab();
    $QcTestLab->setConn($ConnSL);
    $rs = $QcTestLab->UpdateQAItemSpecRemark($itemSpec,$remark);
    echo json_encode($rs);
}

if ($load == "UpdateSpecCert") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $QcTestLab = new QcTestLab();
    $QcTestLab->setConn($ConnSL);
    $rs = $QcTestLab->UpdateQAItemSpecCert($itemSpec,$specCert);
    echo json_encode($rs);
}

