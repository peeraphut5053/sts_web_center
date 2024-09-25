<?php
foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}
include "./initial.php";
//echo $conn;
$CO = new CustomerOrders();
$CO->setConn($conn_sl);
$rows = $CO->GetRowsAll_Ajax();
echo json_encode($rows) ;
sqlsrv_close($conn);