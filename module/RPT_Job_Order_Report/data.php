<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    if (is_array($value)) {
        // ถ้าเป็น array ให้วนลูปอีกครั้งเพื่อ trim แต่ละ element
        foreach ($value as $subKey => $subValue) {
            $$key[$subKey] = trim($subValue);
        }
    } else {
        $$key = trim($value);
    }
}

include "../../initial.php";

if ($load == "wc") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $STS_Custom = new JOBORDER();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->GetWorkCenter();
    echo json_encode($rs);
} else if ($load == "Search") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $STS_Custom = new JOBORDER();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->SearchJobOrderReport($Item , $wc,$whereClause);
    echo json_encode($rs);
} else if ($load == "Job") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $STS_Custom = new JOBORDER();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->SaveJobOrder($job, $job_order);
    echo json_encode($rs);
} else if ($load == "Close") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $STS_Custom = new JOBORDER();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->CloseJobOrder($job);
    echo json_encode($rs);
}