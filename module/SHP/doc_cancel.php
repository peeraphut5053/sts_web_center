<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "./initial.php";
$CallModel = new CallModel();
$CallModel->SHP_Models();


$Head = new BoatPlanHead();
$Head->setConn($ConnWebApp);
$Head->_HeadCode = $HeadCode ;

$List = new BoatPlanList();
$List->setConn($ConnWebApp);
$List->_HeadCode = $HeadCode ;


if($Type=='Delete'){
    $Head->delete();
    $List->deleteWithHeader();
}else{
    $Head->_Cancel = $Cancel ; 
    $Head->updateStatus();
}

//echo $rs0 ;

