<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}
require_once "../initial.php";

$CallModel = new CallModel();
$CallModel->SyteLine_Models();
$Trans = new JobOrder();
$Trans->setConn($ConnSL);

if ($load == "RPT_JOBSLIT_table") {
    $Trans = $Trans->GetJobSlit($txtFromDate,$txtToDate,$txtref_num,$txtItem,$txtw_c);
    echo json_encode($Trans);
} 


