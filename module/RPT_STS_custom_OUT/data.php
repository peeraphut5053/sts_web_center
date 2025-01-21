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
    $rs = $STS_Custom->GetSTS_Custom_Out($StartDate, $EndDate, $date_submit);
    echo json_encode($rs);
}

if ($load == "InsertCustomOut") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $STS_Custom = new STS_Custom();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->AddSTS_Custom_Out($doc_no, $boatnote, $date, $item, $boat_name, $boat_no, $inv_no, $bundle, $weight_net, $weight_gross,$weight_zinc,$weight_nonzinc, $cust_po, $value_out, $pier, $forValue, $currency, $BL_no, $loc_name,$loc_name2,$loc_name3,$loc_name4);
    echo json_encode($rs);
}   

if ($load == "update_sts_custom_out") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $STS_Custom = new STS_Custom();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->UpdateSTS_Custom_Out($doc_no, $field, $newValue);
    echo json_encode($rs);
}

