<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
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


