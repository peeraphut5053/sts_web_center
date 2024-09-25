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