<?php

header("Access-Control-Allow-Origin: *");
while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../../initial.php";

if ($load == "ajax") {

    $CM = new CallModel();
    $CM->SyteLine_Models();
    $GL = new PurchaseOrder();
    $GL->setConn($ConnSL);
    $GL->_Year = $Year;
    $GL->_Month = $Month;
    $GL->_Saleside = $Saleside;
    $GL->_Type = $Type;
    $GL->_Vend_num = "1";
    $GL->_item_group = "1";
    $GLS = $GL->GetRows_SP_RPT_MAT_PURCHASE();
    $CM = null;
    $GL = null;

    echo json_encode($GLS);
}
