<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

include "../../initial.php";
$CallModel = new CallModel();
$CallModel->SyteLine_Models();

$STS_Custom = new AD();
$STS_Custom->setConn($ConnSL);

if ($load == "Insert_PackingCost") {
    $doc_no = $_POST["por"];
    for ($i=0;$i<count($doc_no);$i++){
        $CallModel = new CallModel();
        $CallModel->SyteLine_Models();
        $QcTestLab[$i] = new AD();
        $QcTestLab[$i]->setConn($ConnSL);
        $QcTestLab[$i] = $QcTestLab[$i]->Insert_PackingCost($_POST["por"][$i],$_POST["item"][$i],$_POST["export"][$i],$_POST["domestic"][$i]);
        echo json_encode($doc_no[$i]);  
    }
}

if ($load == "Report_PackingCost") {
    $rs = $STS_Custom->Report_PackingCost($por, $PackingCostItem);
    echo json_encode($rs);
}

if ($load == "Update_PackingCost") {
    $rs = $STS_Custom->Update_PackingCost($item, $field, $value);
    echo json_encode($rs);
}

if ($load == "DeleteADpackingCost") {
    $rs = $STS_Custom->DeleteADpackingCost($item);
    echo json_encode($rs);
}

if ($load == "Report_AD_itemSIZEH") {
    $rs = $STS_Custom->Report_AD_itemSIZEH($item);
    echo json_encode($rs);
}


if ($load == "SaveADitemSIZEH") {
    $rs = $STS_Custom->SaveADitemSIZEH($item, $inch, $mm, $sizeh);
    echo json_encode($rs);
}

if ($load == "UpdateADitemSIZEH") {
    $rs = $STS_Custom->UpdateADitemSIZEH($item, $field, $value);
    echo json_encode($rs);
}

if ($load == "DeleteADitemSIZEH") {
    $rs = $STS_Custom->DeleteADitemSIZEH($item);
    echo json_encode($rs);
}

if ($load == "Report_AD_itemGradeSch") {
    $rs = $STS_Custom->Report_AD_itemGradeSch($item, $spec);
    echo json_encode($rs);
}

if ($load == "SaveADitemGradeSch") {
    $rs = $STS_Custom->SaveADitemGradeSch($item, $spec, $gradeh, $schh);
    echo json_encode($rs);
}

if ($load == "UpdateADitemGradeSch") {
    $rs = $STS_Custom->UpdateADitemGradeSch($item, $field, $value);
    echo json_encode($rs);
}

if ($load == "DeleteADitemGradeSch") {
    $rs = $STS_Custom->DeleteADitemGradeSch($item);
    echo json_encode($rs);
}