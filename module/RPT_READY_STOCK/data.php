<?php

header("Access-Control-Allow-Origin: *");

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
    $item = isset($item) ? $item : "";
    $loc = isset($loc) ? $loc : "";
    $sql = "EXEC [dbo].[STS_WebApp_Stock] @item = ?, @loc = ?";
    $params = array($item, $loc);
    $stmt = sqlsrv_query($ConnSL, $sql, $params);
    $rows = array();

    if ($stmt === false) {
        echo json_encode(array(
            "success" => false,
            "message" => "Cannot load ready stock report",
            "errors" => sqlsrv_errors()
        ));
        exit;
    }

    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        foreach ($row as $field => $value) {
            if ($value instanceof DateTime) {
                $row[$field] = $value->format("Y-m-d H:i:s.v");
            }
        }
        $rows[] = $row;
    }

    echo json_encode($rows);
} else if ($load == "GetLocationAll") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Loc = new SlLocation();
    $Loc->setConn($ConnSL);
    $Loc = $Loc->GetLocationAll();
    echo json_encode($Loc);
}

?>
