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
require_once __DIR__ . "/../../include/class.SeaweedFS.php";

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
        
        $files = $_FILES['file'];
        $allowed_types = array('jpg', 'jpeg', 'png');
        $file_count = count($files['name']);
        
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
            $clean_doc = substr(preg_replace('/[^A-Za-z0-9_-]/', '-', $params['do_num']), 0, 23);
            $file_name = 'CP_' . $clean_doc . '_' . date('Ymd_His') . '_' . bin2hex(random_bytes(6)) . '.' . $file_type;
            if (!is_uploaded_file($files['tmp_name'][$i]) || !SeaweedFS::upload('App-Count-Pipe', $file_name, $files['tmp_name'][$i])) {
                $errors[] = "File {$i}: Cannot upload to SeaweedFS";
                continue;
            }
            $saved = sqlsrv_query($ConnSL,
                'INSERT INTO STS_count_pipe_pic (do_num, path, [user]) VALUES (?, ?, ?)',
                array($params['do_num'], $file_name, $params['user']));
            if ($saved === false) {
                SeaweedFS::delete('App-Count-Pipe', $file_name);
                $errors[] = "File {$i}: Cannot save image record";
            } else {
                sqlsrv_free_stmt($saved);
                $uploaded_files[] = array('path' => $file_name, 'original_name' => $files['name'][$i]);
            }
        }
        
        // Response เดียวเท่านั้น
        echo json_encode(array(
            'success' => count($uploaded_files) > 0 && empty($errors),
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