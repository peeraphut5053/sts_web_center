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

if ($_POST['load'] == 'CreateCountPipe') {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $STS_Custom = new API_FreeZone();
    $upload_dir = 'upload/';
    $files = $_FILES['file'];
    $allowed_types = array('jpg', 'jpeg', 'png');
    $STS_Custom->setConn($ConnSL);
    $file_count = count($files['name']);
    $rs = $STS_Custom->CreateCountPipe($_POST['do_num'], $_POST['qty_system'], $_POST['qty_human'], $_POST['user'],  $_POST['remark']);
    for ($i = 0; $i < $file_count; $i++) {
        $file_type = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
        $rs = $STS_Custom->InsertImagePath($_POST['do_num'], $file_type, $_POST['user']);
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        if (count($rs) > 0) {
            if (in_array($file_type, $allowed_types)) {
                $file_name = $rs[0]['path'];
                $file_path = $upload_dir . $file_name;
                if (move_uploaded_file($files['tmp_name'][$i], $file_path)) {
                    echo json_encode($rs);
                } else {
                    echo json_encode(array('success' => false, 'message' => 'ไม่สามารถบันทึกไฟล์ได้'));
                }
            }
        }
    }
    
}