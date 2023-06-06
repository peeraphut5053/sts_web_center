<?php

header("Access-Control-Allow-Origin: *");

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
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
