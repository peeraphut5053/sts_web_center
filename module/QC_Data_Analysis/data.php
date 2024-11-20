<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

require_once "../initial.php";

if ($load == "sum") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $BcTag = new BcTag();
    $BcTag->setConn($ConnSL);
    $rs = $BcTag->GetQcDataAnalysisSummary($StartDate, $EndDate);
    echo json_encode($rs);
}

else {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $BcTag = new BcTag();
    $BcTag->setConn($ConnSL);
    $rs = $BcTag->GetQcDataAnalysisSummaryGroup($StartDate, $EndDate,$load);
    echo json_encode($rs);
}