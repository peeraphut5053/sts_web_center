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
$Data = new JobOrder();
$Data->setConn($ConnSL);

if ($load == "Plated") {
    $Data = $Data->PlatedOrderReport();
    echo json_encode($Data);
} else if ($load == "Packing") {
    $Data = $Data->PackingOrderReport($item,$wc);
    echo json_encode($Data);
} else if ($load == "Production") {
    $Data = $Data->ProductionOrderReport();
    echo json_encode($Data);
} else if ($load == "Hot") {
    $Data = $Data->HotRollReport();
    echo json_encode($Data);
}