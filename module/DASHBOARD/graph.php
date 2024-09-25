<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}
require_once "../initial.php";
$load = $_POST["load"];
if ($load == "form") {
    
} else if ($load == "getGraphList") {
    $CallModel = new CallModel();
    $CallModel->WebApp_Models();
    $data = new ChartGraph();
    $data->setConn($ConnWebApp);
    $data = $data->GetGraphList();
    echo json_encode($data);
} else if ($load == "AllSellSummaryGarphLineChart") {
    $CallModel = new CallModel();
    $CallModel->WebApp_Models();
    $data = new ChartGraph();
    $data->setConn($ConnSL);
    $data = $data->AllSellSummaryGarphLineChart();
    echo json_encode($data);
} else if ($load == "selectGarphGroup") {
    $CallModel = new CallModel();
    $CallModel->WebApp_Models();
    $data = new ChartGraph();
    $data->setConn($ConnWebApp);
    $data = $data->selectGarphGroup($id_graph);
    echo json_encode($data);
} else {
    
}