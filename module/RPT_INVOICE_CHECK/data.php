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
    $Trans = $Trans->RPTInvoiceCheck();
    echo json_encode($Trans);
} 


