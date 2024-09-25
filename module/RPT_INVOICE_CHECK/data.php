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
    $Trans = $Trans->RPTInvoiceCheck();
    echo json_encode($Trans);
} 


