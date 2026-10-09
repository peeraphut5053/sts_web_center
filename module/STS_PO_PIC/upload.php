<?php
header('Content-Type: application/json; charset=utf-8');

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    if (is_array($value)) {
        foreach ($value as $subKey => $subValue) {
            $$key[$subKey] = trim($subValue);
        }
    } else {
        $$key = trim($value);
    }
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "../../initial.php";

$upload_dir = __DIR__ . '/files/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$response = array();

if (isset($_FILES['file']) && !empty($po_num)) {
    $file = $_FILES['file'];
    $allowed_types = array('pdf');

    if ($file['error'] === UPLOAD_ERR_OK) {
        $file_type = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (in_array($file_type, $allowed_types)) {
            $timestamp = date('Ymd_His');
            // Clean po_num of unsafe chars
            $clean_po = preg_replace('/[^A-Za-z0-9_-]/', '', $po_num);
            $new_filename = $clean_po . "_" . $timestamp . ".pdf";
            
            // Ensure filename length does not exceed 60 characters for DB column
            if (strlen($new_filename) > 60) {
                $new_filename = substr($clean_po, 0, 10) . "_" . time() . ".pdf";
            }
            $upload_path = $upload_dir . $new_filename;

            $CallModel = new CallModel();
            $CallModel->SyteLine_Models();
            $PO = new PurchaseOrder();
            $PO->setConn($ConnSL);

            // Delete old file if exists
            $sqlOld = "SELECT [path] FROM STS_po_pic WHERE po_num = ?";
            $stmtOld = sqlsrv_query($ConnSL, $sqlOld, array($po_num));
            if ($stmtOld && $rowOld = sqlsrv_fetch_array($stmtOld, SQLSRV_FETCH_ASSOC)) {
                if (!empty($rowOld['path'])) {
                    $oldFilePath = $upload_dir . $rowOld['path'];
                    if (file_exists($oldFilePath)) {
                        @unlink($oldFilePath);
                    }
                }
            }

            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                $username = !empty($user) ? $user : (isset($_SESSION['login_username']) ? $_SESSION['login_username'] : '');
                $saved = $PO->SavePoPic($po_num, $new_filename, $username);

                if ($saved) {
                    $response['success'] = true;
                    $response['message'] = 'อัพโหลดไฟล์ PDF เทียบราคาสำเร็จ';
                    $response['file_name'] = $new_filename;
                    $response['po_num'] = $po_num;
                } else {
                    $response['success'] = false;
                    $response['message'] = 'บันทึกข้อมูลลงฐานข้อมูลไม่สำเร็จ';
                }
            } else {
                $response['success'] = false;
                $response['message'] = 'ไม่สามารถบันทึกไฟล์ลงระบบได้ กรุณาตรวจสอบสิทธิ์ของโฟลเดอร์';
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'กรุณาอัพโหลดไฟล์ PDF เท่านั้น (.pdf)';
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'เกิดข้อผิดพลาดในการอัพโหลด (Error code: ' . $file['error'] . ')';
    }
} else {
    $response['success'] = false;
    $response['message'] = 'ไม่พบไฟล์ที่อัพโหลด หรือไม่ได้ระบุเลขที่ PO';
}

echo json_encode($response);
