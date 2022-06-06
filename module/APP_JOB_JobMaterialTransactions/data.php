<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
require_once "../initial.php";
$CallModel = new CallModel();
$CallModel->SyteLine_Models();
$CallModelObj = new JOBORDER();
$CallModelObj->setConn($ConnSL);


if ($load == "GetDataJob") {
    $GetDataJob = $CallModelObj->GetDataJob($job);
    echo json_encode($GetDataJob);
} else if ($load == "GetDataTag") {
    $GetDataTag = $CallModelObj->GetDataTag($id,$job);
    echo json_encode($GetDataTag);
} else if ($load == "IssueProcess") {
    $IdArr = $_POST['tIdGridCol'];
    $ItemArr = $_POST['tItemGridCol'];
    $LotArr = $_POST['tLotGridCol'];
    $LocArr = $_POST['tLocGridCol'];
    $GetDataTag = $CallModelObj->IssueProcess($IdArr, $ItemArr, $LotArr, $LocArr, $Wc, $Job,$username);
    echo json_encode($GetDataTag);
} else if ($load == "GetBarType") {
    $GetBarType = $CallModelObj->GetBarType($barcode);
    echo json_encode($GetBarType);
} else if ($load == "Getmatltran_mst") {
    $Getmatltran_mst = $CallModelObj->Getmatltran_mst($job);
    echo json_encode($Getmatltran_mst);
}

