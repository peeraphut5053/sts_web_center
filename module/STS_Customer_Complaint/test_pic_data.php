<?php
require_once "c:/MAMP/htdocs/sts_web_center/include/config.php";
require_once "c:/MAMP/htdocs/sts_web_center/include/sqlConn.php";

echo "=== STS_custComplain_pic ===\n";
$res = sqlsrv_query($ConnSL, "SELECT TOP 20 * FROM STS_custComplain_pic ORDER BY createdate DESC");
if ($res) {
    while ($row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)) {
        echo json_encode($row, JSON_UNESCAPED_UNICODE) . "\n";
    }
} else {
    echo "Query error: " . print_r(sqlsrv_errors(), true) . "\n";
}

echo "=== STS_custComplain_hdr ===\n";
$res2 = sqlsrv_query($ConnSL, "SELECT TOP 10 doc_no, receivedDate, customer, invoice FROM STS_custComplain_hdr ORDER BY createdate DESC");
if ($res2) {
    while ($row2 = sqlsrv_fetch_array($res2, SQLSRV_FETCH_ASSOC)) {
        echo json_encode($row2, JSON_UNESCAPED_UNICODE) . "\n";
    }
}
