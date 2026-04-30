<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$jsonInput = json_decode(file_get_contents('php://input'), true);
if (!is_array($jsonInput)) {
    $jsonInput = [];
}

$request = array_merge($_GET, $_POST, $jsonInput);

foreach ($request as $key => $value) {
    $$key = is_array($value) ? $value : trim($value);
}

$load = isset($request['load']) ? trim($request['load']) : '';

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
    $dataArray = isset($request['data']) ? $request['data'] : [];
    if (count($dataArray) > 0) {
        $rs = $STS_Custom->SavePlanning($dataArray);
        echo json_encode($rs);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No data available to save']);
    }
}

if ($load == "GetSavedPlanning") {
    $year = isset($request['year']) ? $request['year'] : date('Y');
    $month = isset($request['month']) ? $request['month'] : date('n');
    $wc = isset($request['wc']) ? $request['wc'] : '';
    $rs = $STS_Custom->GetSavedPlanning($year, $month, $wc);
    echo json_encode($rs);
}

if ($load == "GetReport") {
    $year = isset($request['year']) ? $request['year'] : date('Y');
    $month = isset($request['month']) ? $request['month'] : date('n');
    $rs = $STS_Custom->GetReport($year, $month);
    echo json_encode($rs);
}

if ($load == "GetTarget") {
    $year = isset($request['year']) ? $request['year'] : date('Y');
    $month = isset($request['month']) ? $request['month'] : date('n');
    $rs = $STS_Custom->GetTarget($year, $month);
    echo json_encode($rs);
}

if ($load == "SaveTarget") {
    $data = isset($request['data']) ? $request['data'] : [];

    if (empty($data) && isset($request['wc'])) {
        $data = [[
            'year' => isset($request['year']) ? $request['year'] : date('Y'),
            'month' => isset($request['month']) ? $request['month'] : date('n'),
            'wc' => $request['wc'],
            'time' => isset($request['time']) ? $request['time'] : '00:00',
            'weight' => isset($request['weight']) ? $request['weight'] : 0,
            'day_work' => isset($request['day_work']) ? $request['day_work'] : 0,
            'roll_std' => isset($request['roll_std']) ? $request['roll_std'] : 0,
            'thickness_std' => isset($request['thickness_std']) ? $request['thickness_std'] : 0
        ]];
    }

    if (!empty($data) && isset($data['wc'])) {
        $data = [$data];
    }

    if(empty($data)) {
        echo json_encode(['status' => 'error', 'message' => 'No data']);
        exit;
    }
    $rs = $STS_Custom->SaveTarget($data);
    echo json_encode($rs);
}


