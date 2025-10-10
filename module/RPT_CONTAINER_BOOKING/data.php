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

if ($load == "GetDocList") {
    $Data = $Data->GetDocBookingList();
    echo json_encode($Data);
}

if ($load == "GenerateDoc") {
    $Data = $Data->GenerateDocNumber();
    echo json_encode($Data);
}

if ($load == "GetCity") {
    $Data = $Data->GetCityBooking();
    echo json_encode($Data);
}

if ($load == "GetCustomerByCity") {
    $Data = $Data->GetCustomerByCity($city);
    echo json_encode($Data);
}

if ($load == "GetCoNum") {
    $Data = $Data->GetCoNum($city,$customer);
    echo json_encode($Data);
}

if ($load == "GetReportContainerBooking") {
    $Data = $Data->GetReportContainerBooking($co_num,$cust_po,$cust_name,$city,$sts_po);
    echo json_encode($Data);
}

if ($load == "CreateContainerBooking") {
    $Data = $Data->CreateContainerBooking($doc_num,$co_num,$container_40,$container_45,$booking_number40,$booking_number45,$booking_date,$status_booking);
    echo json_encode($Data);
}

if ($load == "UpdateContainerBooking") {
    $Data = $Data->UpdateContainerBooking($doc_num,$co_num,$container_40,$container_45,$booking_number40,$booking_number45,$booking_date,$status_booking,$old_co_num);
    echo json_encode($Data);
}

if ($load == "GetDataByDocNum") {
    $Data = $Data->GetDataBookingByDocNum($doc_num);
    echo json_encode($Data);
}

if ($load == "ReportBookingLine") {
    $Data = $Data->GetReportBookingLine($doc_num, $co_num, $cust_po, $cust_name, $city, $sts_po);
    echo json_encode($Data);
}

if ($load == "DeleteByDocNum") {
    $Data = $Data->DeleteByDocNum($doc_num, $co_num);
    echo json_encode($Data);
}

if ($load == "ApproveBooking") {
    $Data = $Data->ApproveBooking($doc_num, $approve);
    echo json_encode($Data);
}
