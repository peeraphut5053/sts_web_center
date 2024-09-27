<?php
header("Access-Control-Allow-Origin: *");
while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../../initial.php";

if ($load == "form") {
    
} else if ($load == "ajax") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $AT = new Factory();
    $AT->setConn($ConnSL);
    $AT->_StartDate = $StartDate;
    $AT->_EndDate = $EndDate;
    $General = $AT->RPT_General_ledger_report();
    echo json_encode($General);
} 


