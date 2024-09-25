




<?php

header("Access-Control-Allow-Origin: *");

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

    $Trans = new Invoice();
    $Trans->setConn($ConnSL);
//    $Trans->_start_Jobdate = $txtFromDate;
//    $Trans->_end_Jobdate = $txtToDate;
//    $Trans->_lot = $txtLot;
//    $Trans->_ref_num = $txtref_num;
//    $Trans->_trans_type = $txttrans_type;
//    $Trans->_u_m = $txtu_m;

    $Trans = $Trans->RPT_payment_invoice_checking($InvFromDate,$InvToDate,$inv_num,$cust_num,$date_dif,$credit_term,$type);

    echo json_encode($Trans);
}

