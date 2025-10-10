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

if ($_POST['load'] == 'CreateReturnPic') {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $STS_Custom = new DeliveryOrder();
    $upload_dir = 'upload/';

    $files = $_FILES['file'];
    $allowed_types = array('jpg', 'jpeg', 'png');
    $STS_Custom->setConn($ConnSL);

    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $results = array();
    $errors = array();

    // วนลูปไฟล์ทั้งหมด
    $file_count = count($files['name']);

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
        $rs = $STS_Custom->CreateReturnPic($_POST['doc_no'], $file_type);

        if (count($rs) > 0) {
            $file_name = $rs[0]['path'];
            $file_path = $upload_dir . $file_name;

            // ย้ายไฟล์
            if (move_uploaded_file($files['tmp_name'][$i], $file_path)) {
                $results[] = $rs[0];
            } else {
                $errors[] = array(
                    'file' => $files['name'][$i],
                    'message' => 'ไม่สามารถบันทึกไฟล์ได้'
                );
            }
        } else {
            $errors[] = array(
                'file' => $files['name'][$i],
                'message' => 'ไม่สามารถสร้าง record ในฐานข้อมูลได้'
            );
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