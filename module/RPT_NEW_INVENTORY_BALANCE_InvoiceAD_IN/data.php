<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../../initial.php";

if ($load == "ajax") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $INVEN = new Inventory();
    $INVEN->setConn($ConnSL);
    $INVENS = $INVEN->RPT_NEW_INVENTORY_BALANCE_InvoiceAD_IN($txtStartDate, $txtEndDate);
    $CM = null;
    $INVEN = null;
    echo json_encode($INVENS);
}
 else if ($load == "ajax_itemGradeSch") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $INVEN = new Inventory();
    $INVEN->setConn($ConnSL);
    $INVENS = $INVEN->Select_itemGradeSch($itemGradeSch);
    $CM = null;
    $INVEN = null;
    echo json_encode($INVENS);
}
else if ($load == "Get_itemGrade") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $INVEN = new Inventory();
    $INVEN->setConn($ConnSL);
    $INVENS = $INVEN->GetItemGradeData($itemGrade);
    $CM = null;
    $INVEN = null;
    echo json_encode($INVENS);
}
else if ($load == "SaveitemGrade") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $INVEN = new Inventory();
    $INVEN->setConn($ConnSL);
    $INVENS = $INVEN->InsertItemGrade($itemGrade,$spec,$GRADEH,$SCHH,$saveStat);
    $CM = null;
    $INVEN = null;
    echo json_encode($INVENS);
}
else if ($load == "DeleteteItemGrade") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $INVEN = new Inventory();
    $INVEN->setConn($ConnSL);
    $INVENS = $INVEN->DeleteItemGrade($itemGrade);
    $CM = null;
    $INVEN = null;
    echo json_encode($INVENS);
}
else if ($load == "ajax_itemSIZEH") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $INVEN = new Inventory();
    $INVEN->setConn($ConnSL);
    $INVENS = $INVEN->Select_itemSIZEH($itemSIZEH);
    $CM = null;
    $INVEN = null;
    echo json_encode($INVENS);
}
else if ($load == "Get_itemSizeh") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $INVEN = new Inventory();
    $INVEN->setConn($ConnSL);
    $INVENS = $INVEN->GetItemSizehData($itemSizeh);
    $CM = null;
    $INVEN = null;
    echo json_encode($INVENS);
}
else if ($load == "SaveitemSizeh") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $INVEN = new Inventory();
    $INVEN->setConn($ConnSL);
    $INVENS = $INVEN->InsertItemSizeh($itemSizeh,$inch,$MM,$SIZEH,$saveStat);
    $CM = null;
    $INVEN = null;
    echo json_encode($INVENS);
}
else if ($load == "DeleteteItemSizeh") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $INVEN = new Inventory();
    $INVEN->setConn($ConnSL);
    $INVENS = $INVEN->DeleteItemSizeh($itemSizeh);
    $CM = null;
    $INVEN = null;
    echo json_encode($INVENS);
}
