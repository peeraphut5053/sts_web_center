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
}  else if ($load == "ajax") {
    
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $InvItem = new Invoice();
    $InvItem->setConn($ConnSL);
    $InvItem->_keyword = $keyword;
    $InvItem->_txtFromDate_start = $txtFromDate_start;
    $InvItem->_txtFromDate_end = $txtFromDate_end;
    $InvItem->_cus_type = 'TT';
    $InvItems = $InvItem->GetRPTINV_Outstanding();
    
    $InvItem = null;
    $CallModel = null;
    echo json_encode($InvItems);
}
