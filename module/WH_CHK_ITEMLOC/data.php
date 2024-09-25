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
    $temp->setReplace("{content}", $temp->getTemplate("./template/CER_DO/index.html"));
}  else if ($load == "ajax") {    
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $ItemModel = new ItemSyteLine();
    $ItemModel->setConn($ConnSL);
    $ItemModel->_check_tag = $txtTag;
    $Items = $ItemModel->CheckItemLoc(); 
//    echo $Items ;
    echo json_encode($Items);    
} 



