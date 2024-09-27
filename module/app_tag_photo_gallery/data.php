<?php

function delete_files($target) {
    $target = '../../template/app_tag_photo_gallery/img_tag/' . $target;
    if (is_dir($target)) {
        $files = glob($target . '*', GLOB_MARK); //GLOB_MARK adds a slash to directories returned
        foreach ($files as $file) {
            delete_files($file);
        }
        rmdir($target);
    } elseif (is_file($target)) {
        unlink($target);
    }
}

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../../initial.php";

header('Content-Type: text/html; charset=utf-8');
if ($load == "GetRows") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $BcTagModel = new BcTag();
    $BcTagModel->setConn($ConnSL);
    $Result = $BcTagModel->GetRows();
    $CM = null;
    echo json_encode($Result);
} else if ($load == "GetRowsImgUploadDoHeader") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $BcTagModel = new BcTag();
    $BcTagModel->setConn($ConnSL);
    $Result = $BcTagModel->GetRowsImgUploadDoHeader();
    $CM = null;
    echo json_encode($Result);
} else if ($load == "uploadImg") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $BcTagModel = new BcTag();
    $BcTagModel->setConn($ConnSL);
    delete_files($file_name_1);
    delete_files($file_name_2);
    $CM = $BcTagModel->uploadImg($tag_id, $lot, $file_name_1, $file_name_2);
    echo $CM;
} else if ($load == "uploadImgDoHeader") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $BcTagModel = new BcTag();
    $BcTagModel->setConn($ConnSL);
    $BcTagModel->uploadImgDoHeader($DoHeader_id, $file_name_1, $file_name_2);
    $CM = null;
    echo json_encode($BcTagModel);
} else if ($load == "SearchTagDetail") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $BcTagModel = new BcTag();
    $BcTagModel->setConn($ConnSL);
    $Result = $BcTagModel->SearchTagDetail($tag_id);
    $CM = null;
    echo json_encode($Result);
} else if ($load == "GetImgTagUpload") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Do = new BcTag();
    $Do->setConn($ConnSL);
//    $Do->_start_Jobdate = $txtFromDate;
//    $Do->_end_Jobdate = $txtToDate;
    $Do = $Do->GetImgTagUpload($lot,$FromTagId,$ToTagId);
    echo json_encode($Do);
} else if ($load == "GetDOHeaderForCreateDoc") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Do = new BcTag();
    $Do->setConn($ConnSL);
    $Do = $Do->GetDOHeaderForCreateDoc($DoHeader_id);
    echo json_encode($Do);
} else if ($load == "GetDOListForCreateDoc") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Do = new BcTag();
    $Do->setConn($ConnSL);
    $Do = $Do->GetDOListForCreateDoc($DoHeader_id);
    echo json_encode($Do);
}


   

