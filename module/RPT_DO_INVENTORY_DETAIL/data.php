<?php

header("Access-Control-Allow-Origin: *");

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

require_once "../initial.php";
$CallModel = new CallModel();
$CallModel->SyteLine_Models();

if ($load == "ajax") {
    $Do = new DeliveryOrder();
    $Do->setConn($ConnSL);
    $Do = $Do->GetDoInventoryDetail($do_num, $sts_no,$cust_po);
    echo json_encode($Do);
} elseif ($load == "GetCarSelect") {
    $Do = new DeliveryOrder();
    $Do->setConn($ConnSL);
    $Do = $Do->GetCarSelect();
    echo json_encode($Do);
} elseif ($load == "GetShipSelect") {
    $Do = new DeliveryOrder();
    $Do->setConn($ConnSL);
    $Do = $Do->GetShipSelect();
    echo json_encode($Do);
}elseif($load == "GetDOSelect"){
    $Do = new DeliveryOrder();
    $Do->setConn($ConnSL);
    $Do = $Do->GetDOSelect();
    echo json_encode($Do);
    
}

if ($load == "updateRemark") {
    $Do = new DeliveryOrder();
    $Do->setConn($ConnSL);
    $Do = $Do->updateRemark($lot,$remark);
    echo json_encode($Do);
}


