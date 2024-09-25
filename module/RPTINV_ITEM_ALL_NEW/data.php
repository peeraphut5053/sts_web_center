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

    $InvItem = new Invoice();
    $InvItem->setConn($ConnSL);
    $InvItem = $InvItem->GetInvItem_ALL_NEW($FromDate,$ToDate);

    echo json_encode($InvItem);
} 

