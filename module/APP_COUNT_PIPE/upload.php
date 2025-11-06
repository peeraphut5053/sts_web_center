<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// ไม่ใช้ variable variables - ใช้ array แทน
$params = array();

// รับ GET parameters
foreach ($_GET as $key => $value) {
    $params[$key] = trim($value);
}

// รับ POST parameters
foreach ($_POST as $key => $value) {
    if (is_array($value)) {
        $params[$key] = array_map('trim', $value);
    } else {
        $params[$key] = trim($value);
    }
}

include "../../initial.php";

try {
    if (isset($params['load']) && $params['load'] == 'CreateCountPipe') {
        
        // Validate required fields
        $required = ['do_num', 'qty_system', 'qty_human', 'user'];
        foreach ($required as $field) {
            if (!isset($params[$field]) || $params[$field] === '') {
                throw new Exception("Missing required field: {$field}");
            }
        }
        
        // Validate files
        if (!isset($_FILES['file']) || empty($_FILES['file']['name'][0])) {
            throw new Exception("No files uploaded");
        }
        
        $CallModel = new CallModel();
        $CallModel->SyteLine_Models();
        $STS_Custom = new API_FreeZone();
        $STS_Custom->setConn($ConnSL);
        
        $upload_dir = 'upload/';
        $files = $_FILES['file'];
        $allowed_types = array('jpg', 'jpeg', 'png');
        $file_count = count($files['name']);
        
        // สร้าง directory ถ้ายังไม่มี
        if (!file_exists($upload_dir)) {
            if (!mkdir($upload_dir, 0777, true)) {
                throw new Exception("Cannot create upload directory");
            }
        }
        
        // เรียก CreateCountPipe ครั้งเดียว
        $remark = isset($params['remark']) ? $params['remark'] : '';
        $result = $STS_Custom->CreateCountPipe(
            $params['do_num'], 
            $params['qty_system'], 
            $params['qty_human'], 
            $params['user'], 
            $remark
        );
        
        $uploaded_files = array();
        $errors = array();
        
        // Upload ไฟล์ทีละไฟล์
        for ($i = 0; $i < $file_count; $i++) {
            
            // ข้ามไฟล์ที่มี error
            if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                $errors[] = "File {$i}: Upload error code " . $files['error'][$i];
                continue;
            }
            
            // ตรวจสอบขนาดไฟล์ (max 10MB)
            if ($files['size'][$i] > 10485760) {
                $errors[] = "File {$i}: File too large (max 10MB)";
                continue;
            }
            
            $file_type = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
            
            // ตรวจสอบประเภทไฟล์
            if (!in_array($file_type, $allowed_types)) {
                $errors[] = "File {$i}: Invalid file type (allowed: jpg, jpeg, png)";
                continue;
            }
            
            // บันทึกข้อมูล path ลง database
            $rs = $STS_Custom->InsertImagePath($params['do_num'], $file_type, $params['user']);
            
            if (!empty($rs) && isset($rs[0]['path'])) {
                $file_name = $rs[0]['path'];
                $file_path = $upload_dir . $file_name;
                
                // ย้ายไฟล์
                if (move_uploaded_file($files['tmp_name'][$i], $file_path)) {
                    // เปลี่ยน permission ให้อ่านได้
                    @chmod($file_path, 0644);
                    $uploaded_files[] = array(
                        'path' => $file_name,
                        'original_name' => $files['name'][$i]
                    );
                } else {
                    $errors[] = "File {$i}: Cannot move uploaded file";
                }
            } else {
                $errors[] = "File {$i}: Cannot insert image path to database";
            }
        }
        
        // Response เดียวเท่านั้น
        echo json_encode(array(
            'success' => true,
            'message' => 'Upload completed',
            'uploaded' => count($uploaded_files),
            'total' => $file_count,
            'files' => $uploaded_files,
            'errors' => $errors
        ));
        
    } else {
        throw new Exception("Invalid request");
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(array(
        'success' => false,
        'message' => $e->getMessage()
    ));
}