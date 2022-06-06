<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "./initial.php";
//
$GrnLine = new SLGRN();
$GrnLine->setConn($ConnSL);
$ret = "";
if ($action == "GetTable") {
    $GetTable = $GrnLine->Ajax_GetRows_Limit($limit);
    //  echo count($GetTable);
// echo count($GetTable) ;
    foreach ($GetTable as $index => $rows) {
        $ret = $ret . "<tr>"
                . "<td>" . $rows["vend_num"] . "</td>"
                . "<td>" . $rows["grn_num"] . "</td>"
                . "<td>" . $rows["grn_line"] . "</td>"
                . "<td>" . $rows["po_release"] . "</td>"
                . "<td>" . $rows["container"] . "</td>"
                . "<td>" . $rows["qty_shipped_conv"] . "</td>"
                . "<td>" . $rows["u_m"] . "</td>"
                . "<td>" . $rows["qty_rec"] . "</td>"
                . "<td>" . $rows["qty_rej"] . "</td>"
                . "<td>" . $rows["qty_vouchered"] . "</td>"
                . "</tr>";
    }
    echo $ret;
//    foreach($GetTable as $index => $rows ){
//
//    }
//    $query = "SELECT sts_no,sno,reference,qa,u_date,user,po_date,c_no,i_date FROM po_qc  LIMIT  $limit ";
//
//    $ret = "";
//    if ($result = $mysqli->query($query)) {
//        while ($row = $result->fetch_assoc()) {
//        }
//        $result->free();
//    } else {
//        echo "ERROR ! : " . $mysqli->error;
//    }
//    echo $ret;
} else if ($action == "SearchLine") {
    $s = "";
    $sts_no = $_POST["sts_no"];
    $GetTable = $GrnLine->SearchLine($s);
    echo json_encode($GetTable) ;
    
}
//foreach ($MillTest as $index => $rows ) {
//}
//echo $ret ;
$GrnLine = null;
sqlsrv_close($ConnSL);
