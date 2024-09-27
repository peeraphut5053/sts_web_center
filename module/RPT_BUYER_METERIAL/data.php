<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
require_once "../initial.php";

if ($load == "RPT_BUYER_METERIAL") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $Trans = new Buyer();
    $Trans->setConn($ConnSL);

    $Trans = $Trans->RPT_BUYER_METERIAL($txtFromDate, $txtToDate, $item, $DepartmentName);

    echo json_encode($Trans);
}


