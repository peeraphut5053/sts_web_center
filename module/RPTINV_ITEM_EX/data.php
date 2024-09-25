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
    
} else if ($load == "CustomerSelect") {
    $temp->setReplace("{content}", $temp->getTemplate("./template/RPTITEM_CUST/index.html"));
} else if ($load == "GetListCustomer") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $CustCriteria = " AND LEFT(cust_num,2) ='EX' ";
    $Customer = new Customer();
    $Customer->setConn($ConnSL);
    $Customer->_criteria2 = $CustCriteria;
    $CustomerS = $Customer->GetRowsAddr();
    echo json_encode($CustomerS);
} else if ($load == "ajax") {
    //$Customers = $_POST["Customers"];
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $InvItem = new Invoice();
    $InvItem->setConn($ConnSL);
    //$InvItem->_Customers = $Customers;
    $InvItem->_start_invdate = $txtFromDate;
    $InvItem->_end_invdate = $txtToDate;
    $InvItem->_item = $txtItem;
    $InvItem->_start_inv = $txtFromInv;
    $InvItem->_end_inv = $txtToInv;
    $InvItems = $InvItem->GetInvItem_EX();
    $InvItem = null;
    $CallModel = null;
    echo json_encode($InvItems);
} else if ($load == "ajax_by_group") {
    $InvItem->_StartDate = $txtFromDate;
    $InvItem->_EndDate = $txtToDate;
    $InvItems = $InvItem->GetReportByGroup("EX");
    $InvItem = null;
    $CallModel = null;
    echo json_encode($InvItems);
} else if ($load == "ajax_by_cust") {
    $InvItem->_StartDate = $txtFromDate;
    $InvItem->_EndDate = $txtToDate;
    $InvItems = $InvItem->GetReportByCust("EX");
    $InvItem = null;
    $CallModel = null;
    echo json_encode($InvItems);
} else if ($load == "ajax_by_end_cust") {
    $InvItem->_StartDate = $txtFromDateEndcustomer;
    $InvItem->_EndDate = $txtToDateEndcustomer;
    $InvItem->_Year = $RepByEndCustYear;
    $InvItem->_Month = $RepByEndCustMonth;
    $InvItem->cust_num = $cust_num;
    $InvItem->cust_seq = $cust_seq;
    $InvItems = $InvItem->GetReportByEndCust("EX");
    $InvItem = null;
    $CallModel = null;
    echo json_encode($InvItems);
} else if ($load == "ajax_cust_seq") {
    $InvItems = $InvItem->GetOptionSeqEndCust($cust_num);
    $InvItem = null;
    $CallModel = null;
    echo json_encode($InvItems);
} else if ($load == "ajax_by_inv") {
    $InvItem->_StartDate = $txtFromDateInv;
    $InvItem->_EndDate = $txtToDateInv;
    $InvItems = $InvItem->GetReportByInv();
    $InvItem = null;
    $CallModel = null;
    echo json_encode($InvItems);
} else if ($load == "ajax_by_country") {
    $InvItem->_StartDate = $txtFromDate;
    $InvItem->_EndDate = $txtToDate;
    $InvItems = $InvItem->GetReportByCountry();
    $InvItem = null;
    $CallModel = null;
    echo json_encode($InvItems);
} else if ($load == "ajax_by_city") {
    $InvItem->_StartDate = $txtFromDate;
    $InvItem->_EndDate = $txtToDate;
    $InvItems = $InvItem->GetReportByCity();
    $InvItem = null;
    $CallModel = null;
    echo json_encode($InvItems);
} else if ($load == "GetSelectCust") {
    $CustOptions = $CustModels->GetOptionEndCust();
    echo json_encode($CustOptions);
} else if ($load == "GetSelectCust") {
    $CustOptions = $CustModels->GetOptionEndCust();
    echo json_encode($CustOptions);
} else if ($load == "GetSelectCountry") {
    $CustOptions = $CustModels->GetOptionCountry();
    echo json_encode($CustOptions);
}