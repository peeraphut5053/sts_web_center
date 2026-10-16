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

$response = array();

if (isset($_FILES['file']) && !empty($po_num)) {
    $file = $_FILES['file'];
    $allowed_types = array('pdf');
    $file_seq = isset($_POST['file_seq']) ? intval($_POST['file_seq']) : (isset($_POST['file_index']) ? intval($_POST['file_index']) : 1);
    if ($file_seq !== 2) {
        $file_seq = 1;
    }

    if ($file['error'] === UPLOAD_ERR_OK) {
        $file_type = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (in_array($file_type, $allowed_types)) {
            $timestamp = date('Ymd_His');
            // Clean po_num of unsafe chars
            $clean_po = preg_replace('/[^A-Za-z0-9_-]/', '', $po_num);
            $new_filename = $clean_po . "_" . $file_seq . "_" . $timestamp . ".pdf";
            
            // Ensure filename length does not exceed 60 characters for DB column
            if (strlen($new_filename) > 60) {
                $new_filename = substr($clean_po, 0, 10) . "_" . $file_seq . "_" . time() . ".pdf";
            }

            $CallModel = new CallModel();
            $CallModel->SyteLine_Models();
            $PO = new PurchaseOrder();
            $PO->setConn($ConnSL);

            // Delete old file of this slot from SeaweedFS if exists
            if ($file_seq == 2) {
                $sqlOld = "SELECT [path] FROM STS_po_pic WHERE po_num = ? AND [path] LIKE ?";
                $paramsOld = array($po_num, $clean_po . '_2_%');
            } else {
                $sqlOld = "SELECT [path] FROM STS_po_pic WHERE po_num = ? AND [path] NOT LIKE ?";
                $paramsOld = array($po_num, $clean_po . '_2_%');
            }
            $stmtOld = sqlsrv_query($ConnSL, $sqlOld, $paramsOld);
            if ($stmtOld && $rowOld = sqlsrv_fetch_array($stmtOld, SQLSRV_FETCH_ASSOC)) {
                if (!empty($rowOld['path'])) {
                    SeaweedFS::delete('po-pic', $rowOld['path']);
                }
            }

            // Upload 100% directly to SeaweedFS (NAS Distributed Object Storage)
            $seaweedUploaded = SeaweedFS::upload('po-pic', $new_filename, $file['tmp_name']);

            if ($seaweedUploaded) {
                $username = !empty($user) ? $user : (isset($_SESSION['login_username']) ? $_SESSION['login_username'] : '');
                $saved = $PO->SavePoPic($po_num, $new_filename, $username, $file_seq);

                if ($saved) {
                    $response['success'] = true;
                    $response['message'] = 'อัปโหลดไฟล์ PDF เทียบราคา (ไฟล์ที่ ' . $file_seq . ') สำเร็จ';
                    $response['file_name'] = $new_filename;
                    $response['file_seq'] = $file_seq;
                    $response['po_num'] = $po_num;
                } else {
                    $response['success'] = false;
                    $response['message'] = 'บันทึกข้อมูลลงฐานข้อมูลไม่สำเร็จ';
                }
            } else {
                $response['success'] = false;
                $response['message'] = 'ไม่สามารถอัปโหลดไฟล์ไปยัง SeaweedFS บน NAS ได้ กรุณาตรวจสอบการเชื่อมต่อ';
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'กรุณาอัปโหลดไฟล์ PDF เท่านั้น (.pdf)';
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'เกิดข้อผิดพลาดในการอัปโหลด (Error code: ' . $file['error'] . ')';
    }
} else {
    $response['success'] = false;
    $response['message'] = 'ไม่พบไฟล์ที่อัปโหลด หรือไม่ได้ระบุเลขที่ PO';
}

echo json_encode($response);
