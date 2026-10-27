<?php
header("Access-Control-Allow-Origin: *");
foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

include "../../initial.php";
$CallModel = new CallModel();
$CallModel->SyteLine_Models();
$Data = new Invoice();
$Data->setConn($ConnSL);

if ($load == "ajax" || $load == "Get") {
    $rs = $Data->GetInvoiceByCustomOut($StartDate, $EndDate);
    echo json_encode($rs);
}

sqlsrv_close($ConnSL);
?>

