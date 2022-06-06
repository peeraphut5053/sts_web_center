<?php
while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "./initial.php";
//echo $conn;
$CO = new CustomerOrders();
$CO->setConn($conn_sl);
$rows = $CO->GetRowsAll_Ajax();
echo json_encode($rows) ;
sqlsrv_close($conn);