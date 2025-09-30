<?php

header("Access-Control-Allow-Origin: *");

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

include "../../initial.php";


if ($load == "GetDoList") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $STS_COUNT = new API_FreeZone();
    $STS_COUNT->setConn($ConnSL);
    $rs = $STS_COUNT->GetDoList();
    echo json_encode($rs);
}

if ($load == "GetReportCountPipe") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $STS_COUNT = new API_FreeZone();
    $STS_COUNT->setConn($ConnSL);
    $rs = $STS_COUNT->GetReportCountPipe($do_num, $start_date, $end_date);
    echo json_encode($rs);
}
