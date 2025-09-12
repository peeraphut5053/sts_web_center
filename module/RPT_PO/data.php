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

if ($load == "GetReportPO") {
    $Data = $Data->GetReportPO($StartDate, $EndDate);
    echo json_encode($Data);
}