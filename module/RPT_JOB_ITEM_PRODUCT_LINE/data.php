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
if ($load == "form") {
    
} else if ($load == "QtyItemLocation") {
    $Data = $Data->QtyItemLocation($item);
    echo json_encode($Data);
}else if ($load == "QtyItemWarehouse") {
    $Data = $Data->QtyItemWarehouse($item);
    echo json_encode($Data);
}else if ($load == "QtyLotLoc") {
    $Data = $Data->QtyLotLoc($item,$loc);
    echo json_encode($Data);
}else if ($load == "QtySumMattrans") {
    $Data = $Data->QtySumMattrans($item);
    echo json_encode($Data);
}else if ($load == "QtySumMattransByLoc") {
    $Data = $Data->QtySumMattransByLoc($item,$loc);
    echo json_encode($Data);
}else if ($load == "QtyOnHand") {
    $Data = $Data->QtyOnHand($item);
    echo json_encode($Data);
}