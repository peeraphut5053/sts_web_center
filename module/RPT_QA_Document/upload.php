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
require_once __DIR__ . '/../../include/class.SeaweedFS.php';

if (isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $response = array();
    $allowed_types = ['pdf', 'xls', 'xlsx'];

    // ตรวจสอบข้อผิดพลาด
    if ($file['error'] === UPLOAD_ERR_OK) {
        // ตรวจสอบนามสกุลไฟล์
        $file_type = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (in_array($file_type, $allowed_types)) {
            $new_filename = basename(str_replace('\\', '/', $file['name']));
            if (empty($code)) {
                $response['success'] = false;
                $response['message'] = 'Missing document code';
            } else if (is_uploaded_file($file['tmp_name']) && SeaweedFS::upload('QA-Document', $new_filename, $file['tmp_name'])) {
                $saved = sqlsrv_query($ConnSL,
                    'UPDATE STS_QA_Document SET [file] = ? WHERE Code = ?',
                    array($new_filename, $code));
                if ($saved !== false && sqlsrv_rows_affected($saved) > 0) {
                    sqlsrv_free_stmt($saved);
                    $response['success'] = true;
                    $response['message'] = 'Upload successful';
                    $response['file_url'] = 'module/RPT_QA_Document/view_file.php?file=' . rawurlencode($new_filename);
                    // Remove the previous attachment only after the replacement is saved.
                    if (!empty($old_file)) {
                        $old_name = basename(str_replace('\\', '/', $old_file));
                        if ($old_name !== $new_filename) {
                            SeaweedFS::delete('QA-Document', $old_name);
                        }
                    }
                } else {
                    $response['success'] = false;
                    $response['message'] = 'Failed to save document attachment';
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
