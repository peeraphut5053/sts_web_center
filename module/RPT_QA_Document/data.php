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
if ($load == "Select") {
    $Data = $Data->SelectQaDocuments($StartDate, $EndDate, $dept, $code, $type);
    echo json_encode($Data);
} else if ($load == "Insert") {
    $Data = $Data->InsertQaDocument($dept, $code, $title, $revision, $issue_date, $type);
    echo json_encode($Data);
} else if ($load == "Update") {
    $Data = $Data->UpdateQaDocument($dept, $code, $title, $revision, $issue_date, $old_code, $type);
    echo json_encode($Data);
} else if ($load == "DeleteFile") {
    $upload_path = $upload_dir . $file;
    unlink($upload_path);
    echo json_encode(true);
} else if ($load == "Delete") {
    $Data = $Data->DeleteQaDocument($code);
    $upload_path = $upload_dir . $file;
    unlink($upload_path);
    echo json_encode($Data);
}