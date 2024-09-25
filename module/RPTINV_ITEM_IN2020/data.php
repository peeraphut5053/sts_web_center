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
$InvItem = new Invoice();
$InvItem->setConn($ConnSL);

if ($load == "form") {
//    $temp = new ReplaceHtml("../../template/SHP/sl_search_co.html");
//    echo $temp->getReplace();
} else if ($load == "CustomerSelect") {
    $temp->setReplace("{content}", $temp->getTemplate("./template/RPTITEM_CUST/index.html"));
} else if ($load == "GetListCustomer") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $CustCriteria = " AND LEFT(cust_num,2) ='TT' ";
    $Customer = new Customer();
    $Customer->setConn($ConnSL);
    $Customer->_criteria2 = $CustCriteria;
    $CustomerS = $Customer->GetRowsAddr();
    echo json_encode($CustomerS);
} else if ($load == "ajax") {
    if (isset($_POST["Customers"])) {
        $Customers = $_POST["Customers"];
        $InvItem->_Customers = $Customers;
    }
    $InvItem->_start_invdate = $txtFromDate;
    $InvItem->_end_invdate = $txtToDate;
    $InvItem->_item = $txtItem;
    $InvItem->_start_inv = $txtFromInv;
    $InvItem->_end_inv = $txtToInv;
    $InvItems = $InvItem->GetInvItem_IN2021();
    $InvItem = null;
    $CallModel = null;
    echo json_encode($InvItems);
} else if ($load == "ajax_by_group") {
    $InvItem->_Year = $RepByGroupYear;
    $InvItem->_Month = $RepByGroupMonth;
    $InvItem->_txtFromDateGroup = $txtFromDate;
    $InvItem->_txtToDateGroup = $txtToDate;
    $InvItems = $InvItem->GetReportByGroup("IN");
    $InvItem = null;
    $CallModel = null;
    echo json_encode($InvItems);
} else if ($load == "ajax_by_cust") {
    $InvItem->_StartDate = $txtFromDateCust;
    $InvItem->_EndDate = $txtToDateCust;
    $InvItem->_Year = $RepByCustYear;
    $InvItem->_Month = $RepByCustMonth;
    $InvItems = $InvItem->GetReportByCust("IN");
    $InvItem = null;
    $CallModel = null;
    echo json_encode($InvItems);
}