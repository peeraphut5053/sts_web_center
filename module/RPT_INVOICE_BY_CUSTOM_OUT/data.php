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

if ($load == "GetSizeList") {
    $rs = $Data->GetExportSizeList();
    echo json_encode($rs);
} else if ($load == "ajax" || $load == "Get") {
    $inv_num = isset($inv_num) ? $inv_num : "";
    $item_size = isset($item_size) ? $item_size : "";
    $rs = $Data->GetInvoiceByCustomOut($StartDate, $EndDate, $inv_num, $item_size);
    echo json_encode($rs);
}

sqlsrv_close($ConnSL);
?>
