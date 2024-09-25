<?php
header("Access-Control-Allow-Origin: *");

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

include "../../initial.php";
if ($load == "form") {
//    $temp = new ReplaceHtml("../../template/SHP/sl_search_co.html");
//    echo $temp->getReplace();
} else if ($load == "CustomerSelect") {
    $temp->setReplace("{content}", $temp->getTemplate("./template/RPTDO_PENDING/index.html"));
} else if ($load == "RPTDO_PENDING_1") {

    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $DOSEQ = new DeliveryOrder();
    $DOSEQ->setConn($ConnSL);
    $DOSEQ->_txtDoDateFrom = $txtFromDate;
    $DOSEQ->_txtDoDateTo = $txtToDate;
    $DOPENDING = $DOSEQ->GetDoPending();
    echo json_encode($DOPENDING);
} else if ($load == "DO_PENDING_REMARK") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $DOSEQ = new DeliveryOrder();
    $DOSEQ->setConn($ConnSL);
    echo $DOSEQ->DO_PENDING_REMARK($co_num,$co_line,$do_num,$do_line,$do_seq,$lot,$remark);
}
