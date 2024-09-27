<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../../initial.php";

if ($load == "form") {
//    $temp = new ReplaceHtml("../../template/SHP/sl_search_co.html");
//    echo $temp->getReplace();
} else if ($load == "ajax") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Item = new ItemSyteLine();
    $Item->setConn($ConnSL);
    $Item->_StartDate = $txtStartDate;
    $Item->_ToDate = $txtEndDate;
    $Prods = $Item->GetAllProductCode();
    echo json_encode($Prods);
} else if ($load == "detail") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $InvItem = new Invoice();
    $InvItem->setConn($ConnSL);
    $InvItem->_item_group = $item_group;
    $InvItem->_start_invdate = $StartDate;
    $InvItem->_end_invdate = $EndDate;
    $InvItems = $InvItem->GetInvItem2();
    echo json_encode($InvItems);
}
