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

if ($load == "save") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $STS_Custom = new Buyer();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->Save_store_pass_hdr($doc_no,$item_out,$date_out,$po,$dept,$company,$car,$detail,$user);
    echo json_encode($rs);
}

if ($load == "saveIN") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $STS_Custom = new Buyer();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->Save_store_pass_line($doc_no,$item_in,$date_in,$remark,$user);
    echo json_encode($rs);
}

if ($load == "loadOut") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $STS_Custom = new Buyer();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->Get_store_pass_hdr($StartDate, $EndDate,$doc_no);
    echo json_encode($rs);
}

if ($load == "loadIn") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $STS_Custom = new Buyer();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->Get_store_pass_line($StartDate, $EndDate,$doc_no);
    echo json_encode($rs);
}

if ($load == "saveExtraIN") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $STS_Custom = new Buyer();
    $STS_Custom->setConn($ConnSL);

    $rs = $STS_Custom->SaveExtra_store_pass_line($doc_no,$data,$type);
    echo json_encode($rs);
}

if ($load == "saveExtraOut") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $STS_Custom = new Buyer();
    $STS_Custom->setConn($ConnSL);

    $rs = $STS_Custom->SaveExtra_store_pass_hdr($doc_no,$data,$type);
    echo json_encode($rs);
}