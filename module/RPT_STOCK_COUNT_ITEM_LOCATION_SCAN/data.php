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
$Item = new SlLocation();
$Item->setConn($ConnSL);

if ($load == "ajax") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Loc = new SlLocation();
    $Loc->setConn($ConnSL);
    $Loc->_loc = $loc;
    $Loc = $Loc->GetRowsAll();
    echo json_encode($Loc);
} else if ($load == "GetLocationAll") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Loc = new SlLocation();
    $Loc->setConn($ConnSL);
    $Loc = $Loc->GetLocationAll();
    echo json_encode($Loc);
} else if ($load == "ByItemBarcode") {
    $Item = $Item->GetItemDetail($loc, $item);
    echo json_encode($Item);
} else if ($load == "ByStsTag") {
    $Item = $Item->GetItemDetailByStsTag($tag);
    echo json_encode($Item);
} else if ($load == "GetCountStockList") {
    $CallModel = new CallModel();
    $CallModel->WebApp_Models();
    $CountStock = new CountStock();
    $CountStock->setConn($ConnSL);
    $CountStockList = $CountStock->GetCountStockList($loc,$id_hdr);
    echo json_encode($CountStockList);
} else if ($load == "CreateCountStockList") {
    $CallModel = new CallModel();
    $CallModel->WebApp_Models();
    $CountStock = new CountStock();
    $CountStock->setConn($ConnSL);
    $item_code = $_POST["item_code"];
    $Uf_pack = $_POST["Uf_pack"];
    $sum_pcs = $_POST["sum_pcs"];
    $qty_bundle = $_POST["qty_bundle"];
    $qty_pcs = $_POST["qty_pcs"];
    
    $CountStockList = $CountStock->CreateCountStockList($loc, $Uf_pack, $sum_pcs, $item_code,$qty_bundle,$qty_pcs);
    echo json_encode($CountStockList);
} else if ($load == "GetItemListInLocation") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Loc = new SlLocation();
    $Loc->setConn($ConnSL);
    $Loc->_loc = $loc;
    $Loc = $Loc->GetItemListInLocation();
    echo json_encode($Loc);
} else if ($load == "GetCountStockDetail") {
    $CallModel = new CallModel();
    $CallModel->WebApp_Models();
    $CountStock = new CountStock();
    $CountStock->setConn($ConnSL);
    if (!isset($item_code)) {
        $item_code = "";
    }
    $CountStockList = $CountStock->GetCountStockDetail($loc, $item_code,$id_hdr);
    echo json_encode($CountStockList);
} else if ($load == "UpdateCountStockList") {
    $CallModel = new CallModel();
    $CallModel->WebApp_Models();
    $CountStock = new CountStock();
    $CountStock->setConn($ConnSL);
    $CountStockList = $CountStock->UpdateCountStockList($id_hdr, $item_code, $sum_qty_count_pcs,$qty_count_bundle,$qty_count_pcs,$remark);
    echo json_encode($CountStockList);
} else if ($load == "chkStockApprove") {
    $CallModel = new CallModel();
    $CallModel->WebApp_Models();
    $CountStock = new CountStock();
    $CountStock->setConn($ConnSL);
    $CountStockList = $CountStock->chkStockApprove($loc);
    echo json_encode($CountStockList);
} else if ($load == "confirmCheckStock") {
    $CallModel = new CallModel();
    $CallModel->WebApp_Models();
    $CountStock = new CountStock();
    $CountStock->setConn($ConnSL);
    $CountStockList = $CountStock->confirmCheckStock($id_hdr);
    echo json_encode($CountStockList);
}

