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
    $InvItem->_StartDate = $txtFromDate;
    $InvItem->_EndDate = $txtToDate;
    $InvItems = $InvItem->GetReportSalesTotal();
    $InvItem = null;
    $CallModel = null;
    echo json_encode($InvItems);

}


