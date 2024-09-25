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
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $TagItem = new PACKING();
    $TagItem->setConn($ConnSL);
    $TagItem->_start_Tagdate = $txtFromDate;
    $TagItem->_end_Tagdate = $txtToDate;
    $TagItem->_start_Tag = $txtFromTag;
    $TagItem->_end_Tag = $txtToTag;
    $TagItem->_JOB = $txtJOB;
    $TagItem->_LOT = $txtLOT;
    $TagItem->_STS_PO = $txtSTSPO;
    $TagItem->_TYPE = $txtTYPE;
    $TagItem->_NPS = $txtNPS;
    $TagItem->_PcsPerBundle = $txtPCS;
    $TagItem->_Weight = $txtWEIGHT;
    $TagItem->_Bundle = $txtBUNDLE;
    $TagItem->_HeatNo = $txtHEATNO;
    $TagItem->_name = $txtCUST;
    $TagItem->_cust_po = $txtCUSTPO;
    $TagItem->_PORT = $txtPORT;
    $TagItem->_ITEM = $txtITEM;
    $TagItem->_SIZE = $txtSIZE;
    $TagItem->_loc = $txtLOCATION;

    
    $TagItems = $TagItem->GetPackingListItem();
    $TagItem = null;
    $CallModel = null;
    echo json_encode($TagItems);
}   