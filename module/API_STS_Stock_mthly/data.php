<?php

header("Access-Control-Allow-Origin: *");


while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
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



