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
$upload_dir = 'files/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if (isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $response = array();
    $allowed_types = ['pdf', 'xls', 'xlsx'];

    // ตรวจสอบข้อผิดพลาด
    if ($file['error'] === UPLOAD_ERR_OK) {
        // ตรวจสอบนามสกุลไฟล์
        $file_type = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (in_array($file_type, $allowed_types)) {
            // ลบไฟล์เก่าถ้ามี
            if (isset($old_file) && !empty($old_file)) {
                $old_upload_path = $upload_dir . $old_file;
                if (file_exists($old_upload_path)) {
                    unlink($old_upload_path);
                }
            }

            // ใช้ชื่อไฟล์
            $new_filename = $file['name'];
            $upload_path = $upload_dir . $new_filename;
            
            // ย้ายไฟล์
            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                $response['success'] = true;
                $response['message'] = 'อัพโหลดสำเร็จ';
                $response['file_url'] = $upload_dir . $new_filename;
                
                try {
                    $CallModel = new CallModel();
                    $CallModel->SyteLine_Models();
                    $STS_Custom = new QcTestLab();
                    $STS_Custom->setConn($ConnSL);
                    $rs = $STS_Custom->UploadFileQaDocument($code, $new_filename);
                } catch (Exception $e) {
                    // บันทึกข้อผิดพลาดแต่ไม่แสดงให้ผู้ใช้เห็น
                    error_log("Error calling model: " . $e->getMessage());
                }
            } else {
                $response['success'] = false;
                $response['message'] = 'ไม่สามารถบันทึกไฟล์ได้';
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'กรุณาอัพโหลดไฟล์ PDF เท่านั้น';
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'เกิดข้อผิดพลาดในการอัพโหลด: ' . $file['error'];
    }
} else {
    $response['success'] = false;
    $response['message'] = 'ไม่พบไฟล์ที่อัพโหลด';
}

// ส่งคืนข้อมูล JSON
header('Content-Type: application/json');
echo json_encode($response);
