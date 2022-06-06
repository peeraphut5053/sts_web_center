<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
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


