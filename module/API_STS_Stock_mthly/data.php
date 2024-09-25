<?php

header("Access-Control-Allow-Origin: *");


foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}
require_once "../initial.php";

if ($load == "API_STS_Stock_mthly") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    $Trans = $Trans->API_STS_Stock_mthly($month, $year);
    echo json_encode($Trans);
}



