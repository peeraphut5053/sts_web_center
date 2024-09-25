<?php
header("Access-Control-Allow-Origin: *");

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

include "../../initial.php";

if ($load == "ajax") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $AT = new Factory();
    $AT->setConn($ConnSL);
    $AT->_StartDate = $StartDate;
    $AT->_EndDate = $EndDate;
    $General = $AT->RPT_General_ledger_report();
    echo json_encode($General);
} 


