<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}
require_once "../initial.php";

if ($load == "ajax") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Loc = new ItemSyteLine();
    $Loc->setConn($ConnSL);
    $Loc = $Loc->GetNewInventoryMovement($QtyEnter,$Dst_Item,$Dst_Date,$TransTypeRun);
    echo json_encode($Loc);
//    echo $Dst_Date;
}

