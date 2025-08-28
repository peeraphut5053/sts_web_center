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

if ($_POST['load'] == 'CreateRework') {
    $upload_dir = 'upload/';
    $files = $_FILES['file'];
    $allowed_types = array('jpg', 'jpeg', 'png');
    $file_type = strtolower(pathinfo($files['name'], PATHINFO_EXTENSION));
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->CreateRework($_POST['due_rework'], $_POST['user_req'], $_POST['dep_req'], $_POST['item_rework'], $_POST['new_item_rework'], $_POST['reason_rework'], $_POST['detail_rework'], $_POST['qty_rework'], $_POST['username'], $file_type, $_POST['remark'], $_POST['wcS'], $_POST['wcPn'], $_POST['wcG'], $_POST['wcM'], $_POST['wcT'], $_POST['wcPk']);
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    if (count($rs) > 0) {
        if (in_array($file_type, $allowed_types)) {
            $file_name = $rs[0]['pic'];
            $file_path = $upload_dir . $file_name;
            if (move_uploaded_file($files['tmp_name'], $file_path)) {
                echo json_encode($rs);
            } else {
                echo json_encode(array('success' => false, 'message' => 'ไม่สามารถบันทึกไฟล์ได้'));
            }
        }
    }
}

if ($_POST['load'] == 'AddNewItemRework') {
    $upload_dir = 'upload/';
    $files = $_FILES['file'];
    $allowed_types = array('jpg', 'jpeg', 'png');
    $file_type = strtolower(pathinfo($files['name'], PATHINFO_EXTENSION));
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->AddNewItemRework($_POST['doc_rework'], $_POST['item_rework'], $_POST['new_item_rework'], $_POST['reason_rework'], $_POST['detail_rework'], $_POST['qty_rework'], $_POST['username'], $file_type, $_POST['wcS'], $_POST['wcPn'], $_POST['wcG'], $_POST['wcM'], $_POST['wcT'], $_POST['wcPk']);
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    if (count($rs) > 0) {
        if (in_array($file_type, $allowed_types)) {
            $file_name = $rs[0]['pic'];
            $file_path = $upload_dir . $file_name;
            if (move_uploaded_file($files['tmp_name'], $file_path)) {
                echo json_encode($rs);
            } else {
                echo json_encode(array('success' => false, 'message' => 'ไม่สามารถบันทึกไฟล์ได้'));
            }
        }
    }
}

if ($_POST['load'] == 'UpdateRework') {
    $upload_dir = 'upload/';
  
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->UpdateRework($_POST['doc_rework'], $_POST['seq'], $_POST['due_rework'], $_POST['user_req'], $_POST['dep_req'], $_POST['item_rework'], $_POST['new_item_rework'], $_POST['reason_rework'], $_POST['detail_rework'], $_POST['qty_rework'], $_POST['remark'], $_POST['wcS'], $_POST['wcPn'], $_POST['wcG'], $_POST['wcM'], $_POST['wcT'], $_POST['wcPk']);
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    if (count($rs) > 0 && isset($_FILES['file'])) {
         $files = $_FILES['file'];
         $allowed_types = array('jpg', 'jpeg', 'png');
         $file_type = strtolower(pathinfo($files['name'], PATHINFO_EXTENSION));
         // remove old file
         if (file_exists($upload_dir . $rs[0]['pic'])) {
             unlink($upload_dir . $rs[0]['pic']);
         }
         
         if (in_array($file_type, $allowed_types)) {
            $file_name = $_POST['doc_rework'] . '-' . sprintf('%03d', $_POST['seq']) . '.' . $file_type;
            $file_path = $upload_dir . $file_name;
            if (move_uploaded_file($files['tmp_name'], $file_path)) {
                $STS_Custom->UpdateReworkPic($_POST['doc_rework'], $_POST['seq'], $file_name);
                echo json_encode($rs);
            } else {
                echo json_encode(array('success' => false, 'message' => 'ไม่สามารถบันทึกไฟล์ได้'));
            }
        }
    } else {
        echo json_encode($rs);
    }
}