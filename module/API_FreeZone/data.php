<?php

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}
require_once "../initial.php";
$CallModel = new CallModel();
$CallModel->SyteLine_Models();
$CallModelObj = new API_FreeZone();
$CallModelObj->setConn($ConnSL);
if ($load == "PostCreateDocument") {
    $PostValues = json_decode(file_get_contents('php://input'), true);
    $CallModelObj->PostCreateDocument($PostValues);
    //echo json_encode($GenDocNumber);
} elseif ($load == "GenDocNumber") {
    $GenDocNumber = $CallModelObj->GenDocNumber();
    echo json_encode($GenDocNumber);
} elseif ($load == "DocumentHeader") {
    $GenDocNumber = $CallModelObj->DocumentHeader();
    echo json_encode($GenDocNumber);
} elseif ($load == "DocumentLine") {
    $GenDocNumber = $CallModelObj->DocumentLine($doc_hdr);
    echo json_encode($GenDocNumber);
} elseif ($load == "exportItemSelect") {
    $GenDocNumber = $CallModelObj->exportItemSelect();
    echo json_encode($GenDocNumber);
} elseif ($load == "SQLQueryReport") {
    $PostValues = json_decode(file_get_contents('php://input'), true);
    //echo print_r($PostValues);
    $GenDocNumber = $CallModelObj->SQLQueryReport($report_name, $PostValues);
    //echo json_encode($searchValue);
    echo json_encode($GenDocNumber);
    //$PostValues = ["doc_hdr" => "dfgsd", "item" => "fgdfgsdfg"];
} elseif ($load == "SearchInputReport") {
    $GenDocNumber = $CallModelObj->SearchInputReport($report_name);
    echo json_encode($GenDocNumber);
} elseif ($load == "exportPoItemSelect") {
    $GenDocNumber = $CallModelObj->exportPoItemSelect($item);
    echo json_encode($GenDocNumber);
} elseif ($load == "STS_freezone_report") {
    $GenDocNumber = $CallModelObj->STS_freezone_report($department_name);
    echo json_encode($GenDocNumber);
} elseif ($load == "STS_freezone_department") {
    $GenDocNumber = $CallModelObj->STS_freezone_department();
    echo json_encode($GenDocNumber);
}
