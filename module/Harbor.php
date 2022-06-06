<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}


//============== Render Page Normal ================//
if(!isset($_POST["ajax"])){
  // $temp->setReplace("{crumb}", "");
  // $temp->setReplace("{PageName}", "Sale Orders");
  // $temp->setReplace("{content}", $temp->getTemplate("./template/SOD/SaleOrders.html"));

}else{
//============== Render Ajax =======================//
  include "./initial.php";
    if($_POST["action"]=="GetItemToDropdown"){
      $Harbor= new Harbor();
      $Harbor->setConn($ConnWebApp);
      $HarborList = $Harbor->GetItemToDropdown("");
      echo json_encode($HarborList) ;
}
}
