<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

require_once "../initial.php";

if ($load == "Scan") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $BcTag = new BcTag();
    $BcTag->setConn($ConnSL);
    $rs = $BcTag->GetTagData($tag_id);
    echo json_encode($rs);
}