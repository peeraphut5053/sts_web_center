<?php

header("Access-Control-Allow-Origin: *");

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

include "../../initial.php";

    $CM = new CallModel();
    $CM->SyteLine_Models();
    $GL = new GeneralLedger();
    $GL->setConn($ConnSL);

if ($load == "ajax") {
    $GLS = $GL->GetRowsGLDetail($selYear, $selMonth, $selMonth2, $Acct, $Unit1);
    echo json_encode($GLS);
    }
    
if ($load == "monthly"){
    $GLS = $GL->GetRowsGLMonthly($Year, $Unit);
    echo json_encode($GLS);
}

if ($load == "department"){
    $GetModel = new Chart();
    $GetModel->setConn($ConnSL);
    $GetModelValue = $GetModel->GetUnit1();
    echo json_encode($GetModelValue);
}
