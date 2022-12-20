<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
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
}
