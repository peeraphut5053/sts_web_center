<?php

header("Access-Control-Allow-Origin: *");

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../../initial.php";

if ($load == "Save") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $STS_Custom = new STS_Custom();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->Add_STS_Custom_groupB($StartDate, $EndDate, $item, $weight_KG, $qty, $bundle, $pcs, $loc);
    echo json_encode($rs);
}

if ($load == "InsertSTS_Custom_In") {
    $doc_no = $_POST["doc_no"];
    for ($i=0;$i<count($doc_no);$i++){
        $CallModel = new CallModel();
        $CallModel->SyteLine_Models();
        $STSCustom[$i] = new STS_Custom();
        $STSCustom[$i]->setConn($ConnSL);
        $STSCustom[$i] = $STSCustom[$i]->InsertSTS_Custom_In($_POST["doc_no"][$i],
        $_POST["date_in"][$i],
        $_POST["date_stock"][$i],
        $_POST["supplier"][$i],
        $_POST["country"][$i],
        $_POST["AD_rate"][$i],
        $_POST["weight_KG"][$i],
        $_POST["value"][$i],
        $_POST["remark"][$i]
        );
        echo json_encode($doc_no[$i]);    
    }
}


if ($load == "InsertSTS_Custom_Out") {
    $doc_no = $_POST["doc_no"];
    for ($i=0;$i<count($doc_no);$i++){
        $CallModel = new CallModel();
        $CallModel->SyteLine_Models();
        $STSCustom[$i] = new STS_Custom();
        $STSCustom[$i]->setConn($ConnSL);
        $STSCustom[$i] = $STSCustom[$i]->InsertSTS_Custom_Out($_POST["doc_no"][$i],
        $_POST["boatnote"][$i],
        $_POST["date"][$i],
        $_POST["item"][$i],
        $_POST["boat_name"][$i],
        $_POST["boat_no"][$i],
        $_POST["inv_no"][$i],
        $_POST["bundle"][$i],
        $_POST["weight_net"][$i],
        $_POST["weight_gross"][$i],
        $_POST["weight_zinc"][$i],
        $_POST["weight_nonzinc"][$i],
        $_POST["cust_po"][$i],
        $_POST["value"][$i],
        $_POST["pier"][$i],
        $_POST["BL_no"][$i],
        $_POST["loc_name"][$i]
        );
        echo json_encode($doc_no[$i]);  
    }
}

if ($load == "InsertSTS_Custom_Scrap") {
    $doc_no = $_POST["doc_no"];
    for ($i=0;$i<count($doc_no);$i++){
        $CallModel = new CallModel();
        $CallModel->SyteLine_Models();
        $STSCustom[$i] = new STS_Custom();
        $STSCustom[$i]->setConn($ConnSL);
        $STSCustom[$i] = $STSCustom[$i]->InsertSTS_Custom_Scrap($_POST["doc_no"][$i],$_POST["date"][$i],$_POST["item"][$i],$_POST["weight_KG"][$i],$_POST["value"][$i],$_POST["stamp_no"][$i],$_POST["ref_doc_no"][$i]
        );
        echo json_encode($doc_no[$i]);  
    }
}
