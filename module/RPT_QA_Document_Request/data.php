<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

include "../../initial.php";
$CallModel = new CallModel();
$CallModel->SyteLine_Models();
$Data = new QcTestLab();
$Data->setConn($ConnSL);
$upload_dir = 'files/';

if ($load == "GetReportQaDocumentRequest") {
    $Data = $Data->GetReportQaDocumentRequest($StartDate, $EndDate, $ID, $cat, $type);
    echo json_encode($Data);
}

if ($load == "NewQaDocumentRequest") {
    $Data = $Data->NewQaDocumentRequest($req, $doc, $user, $dept, $type, $code, $remark);
    echo json_encode($Data);
}

if ($load == "UpdateQaDocumentRequest") {
    $Data = $Data->UpdateQaDocumentRequest($req, $doc, $user, $dept,  $code, $prev_revision, $new_revision, $description, $remark, $type);
    echo json_encode($Data);
}

if ($load == "UpdateNewQaDocumentRequest") {
    $Data = $Data->UpdateNewQaDocumentRequest($ID,$doc, $dept, $type, $code, $remark);
    echo json_encode($Data);
}

if ($load == "EditUpdateQaDocumentRequest") {
    $Data = $Data->EditUpdateQaDocumentRequest($ID, $doc,  $dept,  $code, $prev_revision, $new_revision, $description, $remark, $type);
    echo json_encode($Data);
}

if ($load == "DeleteQaDocumentRequest") {
    $Data = $Data->DeleteQaDocumentRequest($ID);
    echo json_encode($Data);
}

if ($load == "MgrApprove") {
    $Data = $Data->MgrApproveQaDocumentRequest($ID, $user);
    echo json_encode($Data);
}

if ($load == "QaApprove") {
    $Data = $Data->QaApproveQaDocumentRequest($ID, $user);
    echo json_encode($Data);
}

if ($load == "QaUpload") {
    $Data = $Data->QaUploadQaDocumentRequest($ID, $user);
    echo json_encode($Data);
}
