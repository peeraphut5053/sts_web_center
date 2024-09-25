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
}else if ($load == "BOMJobItem") {
    $Data = $Data->BOMJobItem($item);
    echo json_encode($Data);
}else if ($load == "BOMJobItem2") {
    $Data = $Data->BOMJobItem2($item);
    echo json_encode($Data);
}else if ($load == "BOMJobItem3") {
    $Data = $Data->BOMJobItem3($item);
    echo json_encode($Data);
}else if ($load == "BOMJobItem4") {
    $Data = $Data->BOMJobItem4($item);
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
}