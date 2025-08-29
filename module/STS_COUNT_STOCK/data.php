<?php

header("Access-Control-Allow-Origin: *");

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

include "../../initial.php";
$CallModel = new CallModel();
$CallModel->SyteLine_Models();

$STS_Custom = new CountStock();
$STS_Custom->setConn($ConnSL);

if ($load == "CheckStock") {
    $rs = $STS_Custom->CheckStock($location, $item, $qty, $remark, $user);
    echo json_encode($rs);
}

if ($load == "CheckStockLot") {
    $rs = $STS_Custom->CheckStockLot($location, $tag, $qty, $remark, $user);
    echo json_encode($rs);
}

if ($load == "GetLocation") {
    $rs = $STS_Custom->GetLocationItem();
    echo json_encode($rs);
}

if ($load == "GetItem") {
    $rs = $STS_Custom->GetListItem();
    echo json_encode($rs);
}

if ($load == "GetCount") {
    $rs = $STS_Custom->GetCountStock($user,$date);
    echo json_encode($rs);
}

if ($load == "GetItemByTag") {
    $rs = $STS_Custom->GetItemByTag($tag,$loc);
    echo json_encode($rs);
}

