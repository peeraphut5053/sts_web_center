<?php

header("Access-Control-Allow-Origin: *");


while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
require_once "../initial.php";



if ($load == "ajax2") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Trans = new JobOrder();
    $Trans->setConn($ConnSL);

    isset($txtItem) ? $txtItem = $txtItem : $txtItem = '';
    isset($txtlot) ? $txtlot = $txtlot : $txtlot = '';
    isset($txttrans_type) ? $txttrans_type = $txttrans_type : $txttrans_type = '';
    isset($txtref_type) ? $txtref_type = $txtref_type : $txtref_type = '';
    isset($txtw_c) ? $txtw_c = $txtw_c : $txtw_c = '';
    isset($txtloc) ? $txtloc = $txtloc : $txtloc = '';
    isset($txtsts_no) ? $txtsts_no = $txtsts_no : $txtsts_no = '';

    $Trans = $Trans->GetJobMatltrans($txtFromDate, $txtToDate, $txtItem, $txtlot, $txttrans_type, $txtref_type, $txtw_c, $txtloc, $txtsts_no);

    echo json_encode($Trans);
}
