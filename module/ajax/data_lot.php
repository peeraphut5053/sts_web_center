<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
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


