<?php
// ตั้งค่า CORS ถ้าจำเป็น
header('Content-Type: application/json');

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
// ตั้งค่าโฟลเดอร์สำหรับเก็บไฟล์
$upload_dir = 'upload/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}


// ตรวจสอบว่ามีไฟล์ถูกส่งมาหรือไม่
if (isset($_FILES['file'])) {
    $files = $_FILES['file'];
    $response = array();
    $allowed_types = array('jpg', 'jpeg', 'png');

    // ตรวจสอบข้อผิดพลาด
    foreach ($files['error'] as $key => $error) {
        if ($error === UPLOAD_ERR_OK) {
            // ตรวจสอบนามสกุลไฟล์
            $file_type = strtolower(pathinfo($files['name'][$key], PATHINFO_EXTENSION));
            if (in_array($file_type, $allowed_types)) {
                // สร้างชื่อไฟล์ใหม่เพื่อป้องกันการซ้ำ
                $new_filename = uniqid() . '_' . $files['name'][$key];
                $upload_path = $upload_dir . $new_filename;

                // ย้ายไฟล์
                if (move_uploaded_file($files['tmp_name'][$key], $upload_path)) {
                    $response['success'][] = true;
                    $response['message'][] = 'อัพโหลดสำเร็จ';
                    $response['file_url'][] = $upload_dir . $new_filename;
                    $CallModel = new CallModel();
                    $CallModel->SyteLine_Models();
                    $STS_Custom = new Factory();
                    $STS_Custom->setConn($ConnSL);
                    $rs = $STS_Custom->UploadImagesReportRepair($doc_no, $new_filename);
                } else {
                    $response['success'][] = false;
                    $response['message'][] = 'ไม่สามารถบันทึกไฟล์ได้';
                }
            } else {
                $response['success'][] = false;
                $response['message'][] = 'กรุณาอัพโหลดไฟล์รูปภาพเท่านั้น (jpeg, png)';
            }
        } else {
            $response['success'][] = false;
            $response['message'][] = 'เกิดข้อผิดพลาดในการอัพโหลด: ' . $error;
        }
    }
} else {
    $response['success'] = false;
    $response['message'] = 'ไม่พบไฟล์ที่อัพโหลด';
}

echo json_encode($response);
