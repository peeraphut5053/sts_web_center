<?php

session_start();

function delete_files($target) {
    if ($target != "") {
        $target = '../../template/APP_DO_PHOTO_PACKING/APP_DO_PHOTO_PACKING_img/' . $target;
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
}

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

include "../../initial.php";

header('Content-Type: text/html; charset=utf-8');
$CM = new CallModel();
$CM->SyteLine_Models();
$PO_QC = new img_upload();
$PO_QC->setConn($ConnSL);

if ($load == "GetRows") {
    $Result = $PO_QC->GetRows();
    echo json_encode($Result);
} else if ($load == "GetRowsImgUploadDoHeader") {
    $Result = $PO_QC->GetRowsImgUploadDoHeader();
    echo json_encode($Result);
} else if ($load == "uploadImg") {
    if ($file_name != "") {
        delete_files($file_name);
    }
    $Result = $PO_QC->updateImg($tag_id, $file_name_x, $file_name, $create_by);
    echo $Result;
} else if ($load == "updateRemark") {
    $CM = $PO_QC->updateRemark($tag_id, $remark);
    echo $CM;
} else if ($load == "SearchTagDetail") {
    $_SESSION["tag_id"] = $tag_id;
    $Result = $PO_QC->SearchTagDetail($tag_id, $create_by);
    echo json_encode($Result);
} else if ($load == "clearTagBarcode") {
    $_SESSION["tag_id"] = '';
} else if ($load == "DeleteImg") {
    if ($file_name != "") {
        delete_files($file_name);
    }
    $Result = $PO_QC->DeleteImg($tag_id, $file_name_x, $file_name);
} else if ($load == "DeleteTagId") {
    $Result = $PO_QC->DeleteTagId($tag_id);
} else if ($load == "GetImgTagUpload") {
    $Result = $PO_QC->GetImgTagUpload($tag_id, $size_list, $sts_no,$job);
    echo json_encode($Result);
}else if ($load == "GetDOHeaderForCreateDoc") {
    $Result = $PO_QC->GetDOHeaderForCreateDoc();
    echo json_encode($Result);
}else if ($load == "GetDOListForCreateDoc") {
    $Result = $PO_QC->GetDOListForCreateDoc($DoHeader_id);
    echo json_encode($Result);
}else if ($load == "uploadImgDoHeader") {
    $PO_QC->uploadImgDoHeader($DoHeader_id, $file_name_1, $file_name_2);
    echo json_encode($PO_QC);
}

   

