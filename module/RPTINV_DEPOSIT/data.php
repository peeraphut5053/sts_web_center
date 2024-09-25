<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

include "../../initial.php";
if ($load == "form") {
    
} else if ($load == "CustomerSelect") {
    $temp->setReplace("{content}", $temp->getTemplate("./template/RPTITEM_CUST/index.html"));
} else if ($load == "ajax") {
    $Customers = "";
    if (isset($_POST["Customers"])) {
        $Customers = $_POST["Customers"];
    }
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $InvItem = new Invoice();
    $InvItem->setConn($ConnSL);
    $InvItem->_Customers = $Customers;
    $InvItem->_start_invdate = $txtFromDate;
    $InvItem->_end_invdate = $txtToDate;
    $InvItem->_item = $txtItem;
    $InvItem->_start_inv = $txtFromInv;
    $InvItem->_end_inv = $txtToInv;
    $InvItems = $InvItem->GetInvItem_Deposit();
    $InvItem = null;
    $CallModel = null;
    echo json_encode($InvItems);
} else if ($load == "ajax2") {

    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $InvItem = new Invoice();
    $InvItem->setConn($ConnSL);
    $InvItem->_Year = $selYear;
    $InvItem->_Month = $selMonth;
    $InvItems = $InvItem->GetRows_SP_DepositHeader();

    $InvItem = null;
    $CallModel = null;
    echo json_encode($InvItems);
//    echo print_r($InvItems);
    // echo json_encode($InvItems);
}
