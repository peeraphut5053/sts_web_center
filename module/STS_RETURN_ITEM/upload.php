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
require_once __DIR__ . "/../../include/class.SeaweedFS.php";

if ($_POST['load'] == 'CreateReturnPic') {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $STS_Custom = new DeliveryOrder();

    $files = $_FILES['file'];
    $allowed_types = array('jpg', 'jpeg', 'png');
    $dept = isset($_POST['dept']) ? $_POST['dept'] : '';
    if (empty($_POST['doc_no']) || !in_array($dept, array('Sales', 'QC'), true) || !isset($files['name']) || !is_array($files['name'])) {
        echo json_encode(array('success' => false, 'message' => 'Invalid document, department or files'));
        exit;
    }
    $STS_Custom->setConn($ConnSL);


    $results = array();
    $errors = array();

    // วนลูปไฟล์ทั้งหมด
    $file_count = count($files['name']);
    $dept_limit = 0;

    if ($dept == 'Sales') {
        $dept_limit = 5;
    } else if ($dept == 'QC') {
        $dept_limit = 10;
    }

    if ($dept_limit > 0) {
        $pic_count = $STS_Custom->CountReturnPicByDocNo($_POST['doc_no'], $dept);
        $existing_count = count($pic_count) > 0 ? intval($pic_count[0]['pic_count']) : 0;

        if (($existing_count + $file_count) > $dept_limit) {
            echo json_encode(array(
                'success' => false,
                'uploaded' => 0,
                'total' => $file_count,
                'message' => 'upload limit exceeded',
                'errors' => array()
            ));
            exit;
        }
    }

    for ($i = 0; $i < $file_count; $i++) {
        // ข้าม file ที่ว่าง
        if ($files['error'][$i] == UPLOAD_ERR_NO_FILE) {
            continue;
        }

        // ตรวจสอบ error
        if ($files['error'][$i] != UPLOAD_ERR_OK) {
            $errors[] = array(
                'file' => $files['name'][$i],
                'message' => 'เกิดข้อผิดพลาดในการอัพโหลด'
            );
            continue;
        }

        $file_type = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));

        // ตรวจสอบประเภทไฟล์
        if (!in_array($file_type, $allowed_types)) {
            $errors[] = array(
                'file' => $files['name'][$i],
                'message' => 'ประเภทไฟล์ไม่ถูกต้อง (รองรับเฉพาะ jpg, jpeg, png)'
            );
            continue;
        }

        // สร้าง record ในฐานข้อมูล
        $clean_doc = substr(preg_replace('/[^A-Za-z0-9_-]/', '-', $_POST['doc_no']), 0, 17);
        $file_name = 'RT_' . $dept . '_' . $clean_doc . '_' . date('Ymd_His') . '_' . bin2hex(random_bytes(6)) . '.' . $file_type;
        if (!is_uploaded_file($files['tmp_name'][$i]) || !SeaweedFS::upload('Return-Item', $file_name, $files['tmp_name'][$i])) {
            $errors[] = array('file' => $files['name'][$i], 'message' => 'Cannot upload to SeaweedFS');
            continue;
        }
        $saved = sqlsrv_query($ConnSL,
            'INSERT INTO STS_return_pic (doc_no, path, dept) OUTPUT INSERTED.* VALUES (?, ?, ?)',
            array($_POST['doc_no'], $file_name, $dept));
        if ($saved === false) {
            SeaweedFS::delete('Return-Item', $file_name);
            $errors[] = array('file' => $files['name'][$i], 'message' => 'Cannot save image record');
        } else {
            $results[] = sqlsrv_fetch_array($saved, SQLSRV_FETCH_ASSOC);
            sqlsrv_free_stmt($saved);
        }
    }

    // ส่งผลลัพธ์กลับ
    echo json_encode(array(
        'success' => count($results) > 0,
        'uploaded' => count($results),
        'total' => $file_count,
        'data' => $results,
        'errors' => $errors
    ));
}

if ($_POST['load'] == 'DeleteReturnPic') {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $STS_Custom = new DeliveryOrder();
    $STS_Custom->setConn($ConnSL);
    $dept = isset($_POST['dept']) ? $_POST['dept'] : '';
    $pic = $STS_Custom->GetReturnPicByPath($_POST['path'], $dept);

    if (count($pic) == 0) {
        echo json_encode(array(
            'success' => false,
            'message' => 'picture not found'
        ));
        exit;
    }

    $filename = basename(str_replace('\\', '/', $pic[0]['path']));
    if (!SeaweedFS::delete('Return-Item', $filename)) {
        echo json_encode(array('success' => false, 'message' => 'Cannot delete file from SeaweedFS'));
        exit;
    }
    $STS_Custom->DeleteReturnPic($_POST['path'], $dept);

    echo json_encode(array(
        'success' => true
    ));
}
