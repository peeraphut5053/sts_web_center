




<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
require_once "../initial.php";

if ($load == "ajax") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new Invoice();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->RPT_SALES_EX_ITEMS($InvFromDate,$InvToDate,$inv_num,$cust_num,$item,$country);
    echo json_encode($Trans);
}

