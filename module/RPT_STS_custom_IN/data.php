<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

require_once "../initial.php";

if ($load == "ajax") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $STS_Custom = new STS_Custom();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->GetSTS_Custom_In($StartDate, $EndDate);
    echo json_encode($rs);
}