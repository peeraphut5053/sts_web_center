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

$STS_Custom = new Production();
$STS_Custom->setConn($ConnSL);

if ($load == "GetWC") {
    $rs = $STS_Custom->GetWC();
    echo json_encode($rs);
}

if ($load == "GetItemPlanning") {
    $rs = $STS_Custom->GetItemPlanning();
    echo json_encode($rs);
}

if ($load == "GetItemInfo") {
    $rs = $STS_Custom->GetItemInfoPlanning($item);
    if(count($rs) > 0) {
        $row = $rs[0];
        $data = [
            'type' => $row['type'],
            'size' => $row['size'],
            'spec' => $row['spec'],
            'grade' => $row['grade'],
            'sch' => $row['sch'],
            'thick' => $row['thick'],
            'length' => $row['length'],
            'pack' => $row['pack'],
            'length_m' => $row['length_m'],
            'weight_pcs' => $row['weight_pcs'],
            'pcs_hr' => $row['pcs_hr']
        ];
        echo json_encode(['status' => 'success', 'data' => $data]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No item code']);
    }
}

if ($load == "SavePlanning") {
    $dataArray = isset($_POST['data']) ? $_POST['data'] : [];
    if (count($dataArray) > 0) {
        $rs = $STS_Custom->SavePlanning($dataArray);
        echo json_encode($rs);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No data available to save']);
    }
}

if ($load == "GetSavedPlanning") {
    $year = isset($_POST['year']) ? $_POST['year'] : date('Y');
    $month = isset($_POST['month']) ? $_POST['month'] : date('n');
    $wc = isset($_POST['wc']) ? $_POST['wc'] : '';
    $rs = $STS_Custom->GetSavedPlanning($year, $month, $wc);
    echo json_encode($rs);
}

if ($load == "GetReport") {
    $rs = $STS_Custom->GetReport($year, $month);
    echo json_encode($rs);
}

if ($load == "GetTarget") {
    $year = isset($_POST['year']) ? $_POST['year'] : date('Y');
    $month = isset($_POST['month']) ? $_POST['month'] : date('n');
    $rs = $STS_Custom->GetTarget($year, $month);
    echo json_encode($rs);
}

if ($load == "SaveTarget") {
    $data = isset($_POST['data']) ? $_POST['data'] : [];
    if(empty($data)) {
        echo json_encode(['status' => 'error', 'message' => 'No data']);
        exit;
    }
    $rs = $STS_Custom->SaveTarget($data);
    echo json_encode($rs);
}


