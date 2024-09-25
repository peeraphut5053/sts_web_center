<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

require_once "../initial.php";

if ($load == "ajax") {
//    $temp = new ReplaceHtml("../../template/SHP/sl_search_co.html");
//    echo $temp->getReplace();
} else if ($load == "CustomerSelect") {
    $temp->setReplace("{content}", $temp->getTemplate("./template/CER_DO/index.html"));
} else if ($load == "itemList") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Item = new DeliveryOrder();
    $Item->setConn($ConnSL);
    $Item->_ItemSearch = $ItemSearch;
    $Item = $Item->GetDoHdr();
    echo json_encode($Item);
} else if ($load == "GET_do_hdr_mst") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $DVR = new DeliveryOrder();
    $DVR->setConn($ConnSL);
    $DVR->_FromDate = $FromDate;
    $DVR->_ToDate = $ToDate;
    $DVRS = $DVR->GET_do_hdr_mst();
    echo json_encode($DVRS);
    //echo $DVRS;
} else if ($load == "GET_do_line_mst") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $DVR = new DeliveryOrder();
    $DVR->setConn($ConnSL);
    $DVR->_txtDoNum = $do_no;
    $DVRS = $DVR->GET_do_line_mst();
    echo json_encode($DVRS);
}



