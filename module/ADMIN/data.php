<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../../initial.php";
if ($load == "form") {
    
} else if ($load == "pull_tag_to_lot") {
    $query = "EXEC STS_Pull_Stsno_FromTagToLot ";
    $stmt = sqlsrv_prepare($ConnSL, $query);
    if (!$stmt) {
        echo die(print_r(sqlsrv_errors(), true));
    } else {
        echo "success";
    }
} else if ($load == "eagleeyes") {
//     $param_str = $_POST["param_str"];

    $sql = "{call STS_EagleEyes(?)}";
    $params = array(
        array($param_str, SQLSRV_PARAM_IN)
    );

    $query = sqlsrv_query($ConnSL, $sql, $params);
    $lines = "";
    while ($result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $lines = $lines . "<tr><td align='center'>" . $result["ColumnName"] . "</td><td align='center'>" . $result["ColumnValue"] . "</td></tr>";
    }

    echo $lines;
} else if ($load == "check_item_old_bc") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $ItemModel = new ItemSyteLine();
    $ItemModel->setConn($ConnSL);
    $item = $_POST["item"];
    $lot = $_POST["lot"];
    $ArayCheck = array();
    $ItemModel->_ItemS = $item;
    $ItemModel->_LotS = $lot;
    $GetModels = $ItemModel->check_item_old_bc();
    $ItemModel = null;
    echo json_encode($GetModels);
//    echo $grade[0];
} else if ($load == "old_item_bc_save") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $ItemModel = new ItemSyteLine();
    $ItemModel->setConn($ConnSL);
    $ItemModel->_DataInsert = $_POST["DataInsert"];
    $Result = $ItemModel->insert_item_old_bc();
    $ItemModel = null;
    $CallModel = null;    
    echo $Result;

} 

