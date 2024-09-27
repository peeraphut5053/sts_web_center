<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
require_once "../initial.php";

if ($load == "ajax") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Loc = new SlLocation();
    $Loc->setConn($ConnSL);
    $Loc->_loc = $loc;
    $Loc->_item = $item;
    $Loc = $Loc->GetRowsAll();
    echo json_encode($Loc);
} else if ($load == "GetLocationAll") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Loc = new SlLocation();
    $Loc->setConn($ConnSL);
    $Loc = $Loc->GetLocationAll();
    echo json_encode($Loc);
}

