<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}
include "../../initial.php";



if ($load == "form") {
//    $temp = new ReplaceHtml("../../template/SHP/sl_search_co.html");
//    echo $temp->getReplace();
} else if ($load == "CustomerSelect") {
    $temp->setReplace("{content}", $temp->getTemplate("./template/RPTITEM_CUST/index.html"));
} else if ($load == "ajax") {
//    $Customers = $_POST["Customers"];
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $InvItem = new Invoice();
    $InvItem->setConn($ConnSL);
    $InvItem->_start_inv = $txtStartInv;
    $InvItem->_end_inv = $txtEndInv;
    $InvItems = $InvItem->GetInvItem_Edit($SwitchSale);
    $InvItem = null;
    $CallModel = null;
    echo json_encode($InvItems);
} else if ($load == "save") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $result = 0;
    $InvItem = new Invoice();
    $InvItem->setConn($ConnSL);
    $InvItem->_inv_num = $inv_num;
    $InvItem->_co_line = $co_line;
    $InvItem->_item = $item;
    $InvItem->_edit_um = $edit_um;
    $InvItem->_edit_qty = $edit_qty;
    $InvItem->_edit_price = $edit_price;

    $result = $InvItem->SaveInvItem();
    $InvItem = null;
    $CallModel = null;
    echo $result;
}


