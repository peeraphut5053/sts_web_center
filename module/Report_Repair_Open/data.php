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
require_once __DIR__ . "/../../include/class.SeaweedFS.php";

if (empty($doc_no)) {
    echo json_encode(array('success' => false, 'message' => 'Missing repair document number'));
    exit;
}

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
                // Keep the object name readable, URL-safe and at most 60 characters.
                $clean_doc = substr(preg_replace('/[^A-Za-z0-9_-]/', '-', $doc_no), 0, 23);
                $new_filename = 'RR_' . $clean_doc . '_' . date('Ymd_His') . '_' . bin2hex(random_bytes(6)) . '.' . $file_type;

                // ย้ายไฟล์
                if (is_uploaded_file($files['tmp_name'][$key]) && SeaweedFS::upload('Report-Repair', $new_filename, $files['tmp_name'][$key])) {
                    $saved = sqlsrv_query($ConnSL,
                        'INSERT INTO STS_repair_pic (DocNo, path) VALUES (?, ?)',
                        array($doc_no, $new_filename));
                    if ($saved === false) {
                        SeaweedFS::delete('Report-Repair', $new_filename);
                        $response['success'][] = false;
                        $response['message'][] = 'Failed to save repair attachment';
                    } else {
                        sqlsrv_free_stmt($saved);
                        $response['success'][] = true;
                        $response['message'][] = 'Upload successful';
                        $response['file_url'][] = 'module/Report_Repair_Open/view_file.php?file=' . rawurlencode($new_filename);
                    }
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

// Return one JSON document; any failed attachment makes the batch unsuccessful.
$response['success'] = isset($response['success']) && is_array($response['success'])
    && count($response['success']) > 0 && !in_array(false, $response['success'], true);
echo json_encode($response);
