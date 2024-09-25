<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
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


