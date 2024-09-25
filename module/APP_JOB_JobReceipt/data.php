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
$CallModelObj = new JOBORDER();
$CallModelObj->setConn($ConnSL);
if ($load == "job_id_list") {
    $job_id_list = $CallModelObj->job_id_list();
    echo json_encode($job_id_list);
} else if ($load == "loc_list") {
    $loc_list = $CallModelObj->loc_list();
    echo json_encode($loc_list);
} else if ($load == "fill_job") {
    $fill_job = $CallModelObj->fill_job($job);
    echo json_encode($fill_job);
} else if ($load == "tag_scan") {
    $tag_scan = $CallModelObj->tag_scan($tag_id);
    echo json_encode($tag_scan);
} else if ($load == "process_job_receipt") {
    $tag_scan = $CallModelObj->process_job_receipt($job, $suffix, $item, $operNum, $qty, $qty2, $lot, $loc, $transDate, $tag_id);
    echo json_encode($tag_scan);
} else if ($load == "check_process_job_receipt") {
    $tag_scan = $CallModelObj->check_process_job_receipt($lot);
    echo json_encode($tag_scan);
}

