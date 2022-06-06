<?php

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
    $InvItem->_txtFromDate_start = $txtFromDate_start;
    $InvItem->_txtFromDate_end = $txtFromDate_end;
    $InvItem->_txtFromCoNum_start = $txtFromCoNum_start;
    $InvItem->_txtFromCoNum_end = $txtFromCoNum_end;
    $InvItem->_txtFromCusnumOrName = $txtFromCusnumOrName;
    $InvItem->_txtFromItemOrDes = $txtFromItemOrDes;
    
    $InvItem->_cus_type = 'TT';
    $InvItems = $InvItem->GetRPTINV_Outstanding();
    
    $InvItem = null;
    $CallModel = null;
    echo json_encode($InvItems);
}
