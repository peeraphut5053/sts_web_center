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

if ($load == "GetBookingLine") {
    $Data = $Data->GetReportContainerBookingConfirm($doc_num, $co_num, $cust_po, $cust_name, $city, $sts_po);
    echo json_encode($Data);
}

if ($load == "CreateContainerLine") {
    $Data = $Data->CreateContainerLine($doc_num, $co_num, $co_line, $end_cust, $city, $container_no, $bundle);
    echo json_encode($Data);
}

if ($load == "UpdateContainerLine") {
    $Data = $Data->UpdateContainerLine($doc_num, $co_num, $co_line, $container_no, $bundle);
    echo json_encode($Data);
}

if ($load == "UpdateContainerPcs") {
    $Data = $Data->UpdateContainerPcs($doc_num, $co_num, $co_line, $container_no, $bundle);
    echo json_encode($Data);
}

if ($load == "GetContainerLine") {
    $Data = $Data->GetContainerLine($doc_num, $co_num, $co_line);
    echo json_encode($Data);
}

if ($load == "GetTotalWeight") {
    $Data = $Data->GetTotalWeightContainer($doc_num);
    echo json_encode($Data);
}

