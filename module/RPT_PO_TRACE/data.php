<?php
header("Access-Control-Allow-Origin: *");

foreach ($_GET as $key => $data) {
    ${$key} = trim($data);
}

foreach ($_POST as $key => $data) {
    ${$key} = trim($data);
}

require_once "../initial.php";

if ($load == "ajax") {
    $where = array();
    $params = array();

    if (!empty($txtCustPo)) {
        $where[] = "co.cust_po LIKE ?";
        $params[] = substr($txtCustPo, 0, 4) . "%";
    }

    if (!empty($txtCoNum)) {
        $where[] = "co.co_num LIKE ?";
        $params[] = $txtCoNum . "%";
    }

    if (!empty($txtDoNum)) {
        $where[] = "d.do_num LIKE ?";
        $params[] = $txtDoNum . "%";
    }

    if (!empty($txtInvNum)) {
        $where[] = "inv.inv_num LIKE ?";
        $params[] = $txtInvNum . "%";
    }

    $sql = "SELECT DISTINCT
                co.cust_po,
                co.co_num,
                d.do_num,
                inv.inv_num
            FROM co_mst co
                LEFT JOIN inv_item_mst inv ON co.co_num = inv.co_num
                LEFT JOIN AIT_Preship_Do_Seq d ON d.co_num = co.co_num";

    if (count($where) > 0) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }

    $sql .= " ORDER BY co.cust_po, co.co_num, d.do_num, inv.inv_num";

    $stmt = sqlsrv_query($ConnSL, $sql, $params);
    $result = array();

    if ($stmt === false) {
        echo json_encode(array(
            "error" => true,
            "message" => sqlsrv_errors()
        ));
        exit;
    }

    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $result[] = $row;
    }

    echo json_encode($result);
}
