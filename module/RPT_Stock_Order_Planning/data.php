<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}
require_once "../initial.php";
$CallModel = new CallModel();
$CallModel->SyteLine_Models();
$Data = new JobOrder();
$Data->setConn($ConnSL);

if ($load == "BY_Item") {
    $Data = $Data->BY_Item($Item);
    echo json_encode($Data);
}

if ($load == "BY_CO") {
    $Data = $Data->BY_CO($CO);
    echo json_encode($Data);
}

if ($load == "BY_DO") {
    $Data = $Data->BY_DO($DO);
    echo json_encode($Data);
}

if ($load == "ALL") {
    $Data = $Data->ALL();
    echo json_encode($Data);
}