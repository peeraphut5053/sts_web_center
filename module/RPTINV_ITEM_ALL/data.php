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
    $Customers = $_POST["Customers"];
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $InvItem = new Invoice();
    $InvItem->setConn($ConnSL);
    $InvItem->_Customers = $Customers;
    $InvItem->_start_invdate = $txtFromDate;
    $InvItem->_end_invdate = $txtToDate;
    $InvItems = $InvItem->GetInvItem_2("IN");
    $InvItem=null;
    $CallModel=null;
    echo json_encode($InvItems);
   
}
