<?php

header("Access-Control-Allow-Origin: *");

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../../initial.php";

if ($load == "ajax") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $QcTestLab = new QcTestLab();
    $QcTestLab->setConn($ConnSL);
    $rs = $QcTestLab->GetZincCoating($StartDate, $EndDate);
    echo json_encode($rs);
}

if ($load == "InsertSTS_QC_zinc_coat") {
    $doc_no = $_POST["sts_no"];
    for ($i=0;$i<count($doc_no);$i++){
        $CallModel = new CallModel();
        $CallModel->SyteLine_Models();
        $QcTestLab[$i] = new QcTestLab();
        $QcTestLab[$i]->setConn($ConnSL);
        $QcTestLab[$i] = $QcTestLab[$i]->InsertSTS_QC_zinc_coat($_POST["item"][$i],$_POST["lot"][$i],$_POST["sts_no"][$i],$_POST["QC_Pb"][$i],$_POST["QC_weight_coat"][$i]
        );
        echo json_encode($doc_no[$i]);  
    }
}