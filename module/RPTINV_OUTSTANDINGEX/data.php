<?php
header("Access-Control-Allow-Origin: *");

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
}  else if ($load == "ajax") {
    
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $InvItem = new Invoice();
    $InvItem->setConn($ConnSL);
    $InvItem->_txtFromDate_start = $txtFromDate_start;
    $InvItem->_txtFromDate_end = $txtFromDate_end;
    $InvItem->_txtFromCoNum_start = $txtFromCoNum_start;
    $InvItem->_txtFromCoNum_end = $txtFromCoNum_end;
    $InvItem->_txtFromCusnumOrName = $txtFromCusnumOrName;
    $InvItem->_txtFromItemOrDes = $txtFromItemOrDes;
    $InvItem->_txtCity = $txtCity;
    $InvItem->_txtcustomerpo = $txtcustomerpo;
    
    $InvItem->_cus_type = 'EX';
    $InvItems = $InvItem->GetRPTINV_OutstandingEX();
    $InvItem = null;
    $CallModel = null;
    echo json_encode($InvItems);
}  else if ($load == "GetJobDetailModal") {
    
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $InvItem = new Invoice();
    $InvItem->setConn($ConnSL);
  
    $InvItem->_cus_type = 'EX';
    $InvItems = $InvItem->GetJobDetailModal($ord_num,$ord_line);
    $InvItem = null;
    $CallModel = null;
    echo json_encode($InvItems);
}
