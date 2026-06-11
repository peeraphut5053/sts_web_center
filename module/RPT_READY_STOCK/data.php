<?php

header("Access-Control-Allow-Origin: *");
set_time_limit(120);

foreach ($_GET as $key => $data) {
    ${$key} = trim($data);
}
foreach ($_POST as $key => $data) {
    ${$key} = trim($data);
}

require_once "../initial.php";

if (!isset($load)) {
    $load = "";
}

if ($load == "ajax") {
    header("Content-Type: application/json; charset=utf-8");
    $item = isset($item) ? $item : "";
    $loc = isset($loc) ? $loc : "";
    $sql = "EXEC [dbo].[STS_WebApp_Stock] @item = ?, @loc = ?";
    $params = array($item, $loc);
    $options = array("QueryTimeout" => 120);
    $stmt = sqlsrv_query($ConnSL, $sql, $params, $options);

    if ($stmt === false) {
        echo json_encode(array(
            "success" => false,
            "message" => "Cannot load ready stock report",
            "errors" => sqlsrv_errors()
        ));
        exit;
    }

    echo "[";
    $firstRow = true;
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        foreach ($row as $field => $value) {
            if ($value instanceof DateTime) {
                $row[$field] = $value->format("Y-m-d H:i:s.v");
            }
        }

        if (!$firstRow) {
            echo ",";
        }
        echo json_encode($row);
        $firstRow = false;
    }
    echo "]";
} else if ($load == "exportCsv") {
    $item = isset($item) ? $item : "";
    $loc = isset($loc) ? $loc : "";
    $sql = "EXEC [dbo].[STS_WebApp_Stock] @item = ?, @loc = ?";
    $params = array($item, $loc);
    $options = array("QueryTimeout" => 120);
    $stmt = sqlsrv_query($ConnSL, $sql, $params, $options);

    if ($stmt === false) {
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode(array(
            "success" => false,
            "message" => "Cannot export ready stock report",
            "errors" => sqlsrv_errors()
        ));
        exit;
    }

    $fileName = "ready_stock_report_" . date("Ymd_His") . ".csv";
    header("Content-Type: text/csv; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"" . $fileName . "\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    $out = fopen("php://output", "w");
    fwrite($out, "\xEF\xBB\xBF");

    $columns = array(
        "item",
        "unit_weight",
        "qty_on_hand",
        "loc",
        "description",
        "lot",
        "sts_no",
        "city",
        "tag_status",
        "detail",
        "tag",
        "createdate",
        "uf_market",
        "itemType",
        "ready"
    );
    fputcsv($out, $columns);

    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $line = array();
        foreach ($columns as $column) {
            $value = isset($row[$column]) ? $row[$column] : "";
            if ($value instanceof DateTime) {
                $value = $value->format("Y-m-d");
            } else if ($column == "createdate" && $value != "") {
                $value = substr((string) $value, 0, 10);
            }
            $line[] = $value;
        }
        fputcsv($out, $line);
    }

    fclose($out);
} else if ($load == "GetLocationAll") {
    header("Content-Type: application/json; charset=utf-8");
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Loc = new SlLocation();
    $Loc->setConn($ConnSL);
    $Loc = $Loc->GetLocationAll();
    echo json_encode($Loc);
}

?>
