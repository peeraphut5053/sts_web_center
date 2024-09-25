<?php

header("Access-Control-Allow-Origin: *");
foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}
require_once "../initial.php";

if ($load == "RPT_JOBSLIT_table") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);
    
    $Trans = $Trans->GetJobSlit($txtFromDate,$txtToDate,$txtref_num,$txtItem,$txtw_c);

    echo json_encode($Trans);
} 


