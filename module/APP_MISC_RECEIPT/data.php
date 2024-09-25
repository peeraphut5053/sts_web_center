<?php

session_start();

function delete_files($target) {
    if ($target != "") {
        $target = '../../template/APP_QC_ISSUE_REPORT/img_tag/' . $target;
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

header('Content-Type: text/html; charset=utf-8');
$CM = new CallModel();
$CM->SyteLine_Models();
$PO_QC = new po_qc_sl();
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
    $Result = $PO_QC->updateImg($tag_id, $file_name_x, $file_name);
} else if ($load == "updateRemark") {
    $CM = $PO_QC->updateRemark($tag_id, $remark);
    echo $CM;
} else if ($load == "SearchTagDetail") {
    $_SESSION["tag_id"] = $tag_id;
    $Result = $PO_QC->SearchTagDetail($tag_id, $create_by);
    echo json_encode($Result);
} else if ($load == "GetImgTagUpload") {
    $Result = $PO_QC->GetImgTagUpload($tag_id, $size_list, $sts_no);
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
}


   

