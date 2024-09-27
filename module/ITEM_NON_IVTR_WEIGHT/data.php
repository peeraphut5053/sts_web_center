<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../../initial.php";



if ($load == "ajax") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();    
    $ItemModel = new ItemSyteLine();
    $ItemModel->setConn($ConnSL);
    $ItemModel->_item = $txtItem;
    $Items = $ItemModel->GetRowsByItem();
    $CallModel = null;
    $ItemModel = null;
    echo json_encode($Items);
} else if ($load == "save") {
    $ArrItems = $_POST["items"];

    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $InvItem = new ItemSyteLine();
    $InvItem->setConn($ConnSL);
    $InvItem->_ArrItems = $ArrItems;
    $result = $InvItem->SaveItemNonWeight();
    $InvItem = null;
    $CallModel = null;
    echo $result;
}


