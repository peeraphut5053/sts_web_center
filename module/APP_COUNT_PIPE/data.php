<?php

header("Access-Control-Allow-Origin: *");

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    if (is_array($value)) {
        // ถ้าเป็น array ให้วนลูปอีกครั้งเพื่อ trim แต่ละ element
        foreach ($value as $subKey => $subValue) {
            $$key[$subKey] = trim($subValue);
        }
    } else {
        $$key = trim($value);
    }
}

include "../../initial.php";
$CallModel = new CallModel();
$CallModel->SyteLine_Models();
$STS_Custom = new API_FreeZone();

if ($load == "GetDoList") {
    $STS_Custom = $STS_Custom->GetDoList();
    echo json_encode($STS_Custom);
}