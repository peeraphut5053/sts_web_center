<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);


include "../../initial.php";
//=============LOAD SEARCH FORM =========//
if ($load == "render") {
    if ($TargetSearch != "Item") {
        
    }    
    $temp = new ReplaceHtml("../../template/_GLOBAL_SEARCH/$TargetSearch.html");
    $temp->setReplace("{igd_remark}", $igd_remark);

    echo $temp->getReplace();
}
else{
  //============ AJAX ===================//  
    
    
}
?>