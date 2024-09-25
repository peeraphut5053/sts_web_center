<?php

header("Access-Control-Allow-Origin: *");

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

include "../../initial.php";
if ($load == "form") {
    
} else if ($load == "ajax") {
    if (isset($_POST["Cust"])) {
        $Cust = $_POST["Cust"];
    }else{
        $Cust = "";
    }
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $GL = new GeneralLedger();
    $GL->setConn($ConnSL);
    $GL->_StartDate = $StartDate;
    $GL->_EndDate = $EndDate;
    $GL->_Cust = $Cust;
    $GLS = $GL->GetRowsCollectionDay();

    echo json_encode($GLS);
} else {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $GetModel = new Customer();
    $GetModel->setConn($ConnSL);
    $GetModelValue = $GetModel->GetRowsAddr();
    echo json_encode($GetModelValue);
}
