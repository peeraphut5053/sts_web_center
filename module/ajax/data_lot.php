<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "./initial.php";

if ($action == "NewLot") {
    $Lot = new Lot();
//    $Lot->setConn($ConnSL);
    $ret = "";
    $NewLot = $Lot->GenNewLotNum();
    echo $NewLot;
     sqlsrv_close($ConnSL);
}


