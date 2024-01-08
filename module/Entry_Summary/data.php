<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
require_once "../initial.php";

$CallModel = new CallModel();
$CallModel->SyteLine_Models();
$InvEntrySummary = new Invoice();
$InvEntrySummary->setConn($ConnSL);

if ($load == "search") {
    $InvEntrySummary = $InvEntrySummary->Getinv_num($from_invnum, $to_invnum);
    echo json_encode($InvEntrySummary);
}

if ($load == "Insert") {
    $InvEntrySummary = $InvEntrySummary->Insertinv_num($inv_num, $col_name, $valdata);
    echo json_encode($InvEntrySummary);
}