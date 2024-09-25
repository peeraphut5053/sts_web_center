<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

require_once "../initial.php";
$CallModel = new CallModel();
$CallModel->SyteLine_Models();
$Trans = new Inventory();
$Trans->setConn($ConnSL);
if ($load == "Manu_Item_Cost_Update") {
    $Trans = $Trans->Manu_Item_Cost_Update($txtFromDate, $txtToDate);
    echo "update";
} else if ($load == "STS_Recal_Matltran") {
    $Trans = $Trans->STS_Recal_Matltran();
} else if ($load == 'checkStat') {
    $Trans = $Trans->checkStat();
    echo json_encode($Trans);
}


