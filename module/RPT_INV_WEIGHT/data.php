<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

include "../../initial.php";
$CallModel = new CallModel();
$CallModel->SyteLine_Models();
$Data = new Costing();
$Data->setConn($ConnSL);

if ($load == "Get") {
    $Data = $Data->GetReportInvWeight($StartDate, $EndDate);
    echo json_encode($Data);
}