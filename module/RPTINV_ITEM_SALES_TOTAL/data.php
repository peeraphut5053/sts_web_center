<?php

foreach (array_merge($_GET, $_POST) as $key => $data) {
    ${$key} = trim($data);
}
require_once "../initial.php";

if (isset($load) && $load == "ajax") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $InvItem = new Invoice();
    $InvItem->setConn($ConnSL);
    $InvItem->_StartDate = isset($txtFromDate) ? $txtFromDate : "";
    $InvItem->_EndDate = isset($txtToDate) ? $txtToDate : "";
    $InvItem->_item = isset($txtItem) ? $txtItem : "";
    $InvItems = $InvItem->GetReportSalesTotal();
    $InvItem = null;
    $CallModel = null;
    echo json_encode($InvItems);

}


