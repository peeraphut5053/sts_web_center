<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

require_once "../initial.php";

if ($load == "RPT_Trial_Balance") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $INVEN = new Inventory();
    $INVEN->setConn($ConnSL);
    
    $INVEN = $INVEN->RPT_Trial_Balance($txtFromDate,$txtToDate,$txtStartAcc,$txtEndAcc);

    echo json_encode($INVEN);
} 


