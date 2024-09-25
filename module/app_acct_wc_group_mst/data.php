<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}
require_once "../initial.php";

if ($load == "wc_mst") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    
    $Trans = $Trans->wc_mst();

    echo json_encode($Trans);
} 


