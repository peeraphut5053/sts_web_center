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
$Data = new Costing();
$Data->setConn($ConnSL);

if ($load == "GetSalesByWeek") {
    $Data = $Data->GetSalesByWeek($type, $year);
    echo json_encode($Data);
}

if ($load == "GetSalesByMonth") {
    $Data = $Data->GetSalesByMonth($type, $year);
    echo json_encode($Data);
}

if ($load == "GetSalesByYear") {
    $Data = $Data->GetSalesByYear($type, $year);
    echo json_encode($Data);
}