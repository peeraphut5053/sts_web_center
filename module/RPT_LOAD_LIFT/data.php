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
$Data = new CustomerAddrSyteline();
$Data->setConn($ConnSL);

if ($load == "GetReportLoadLift") {
    $Data = $Data->GetReportLoadLift($booknum, $port, $cust);
    echo json_encode($Data);
}

if ($load == "SavePickDate") {
    $Data = $Data->SavePickDate($doc_no, $val, $type);
    echo json_encode($Data);
}

if ($load == "PrintLoadLift") {
    $Data = $Data->GetPrintLoadLift($do_num);
    echo json_encode($Data);
}