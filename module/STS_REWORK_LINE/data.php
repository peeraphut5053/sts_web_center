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
$STS_Custom = new Production();

if ($load == "GetDept") {
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->GetDeptRework();
    echo json_encode($rs);
}

if ($load == "GetItem") {
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->GetItemRework();
    echo json_encode($rs);
}

if ($load == "GetReason") {
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->GetReworkReason();
    echo json_encode($rs);
}

if ($load == "GetDoc") {
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->GetDocRework();
    echo json_encode($rs);
}

if ($load == "GetReworkByDoc") {
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->GetReworkByDoc($doc_no);
    echo json_encode($rs);
}

if ($load == "UpdateHdr") {
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->UpdateReworkHdr($doc_rework, $due_rework, $user_req, $dep_req, $remark);
    echo json_encode($rs);
}