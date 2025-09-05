<?php
header("Access-Control-Allow-Origin: *");

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
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
    $InvItems = $InvItem->GetRPTINV_OutstandingEX($fromDate, $toDate, $cust_po, $sts_po);
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
