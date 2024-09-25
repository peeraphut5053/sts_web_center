




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
    $Trans = new Invoice();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->RPT_SALES_IN_ITEMS($InvFromDate,$InvToDate,$inv_num,$cust_num,$item);
    echo json_encode($Trans);
}

