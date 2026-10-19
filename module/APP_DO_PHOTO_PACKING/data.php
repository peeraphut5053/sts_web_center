<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require __DIR__ . '/../../initial.php';
require_once __DIR__ . '/storage.php';
header('Content-Type: application/json; charset=utf-8');
$request = array_merge($_GET, $_POST);
$load = isset($request['load']) ? $request['load'] : '';
$transaction = false;
try {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $model = new img_upload();
    $model->setConn($ConnSL);
    switch ($load) {
        case 'SearchTagDetail':
            $tag = isset($request['tag_id']) ? trim($request['tag_id']) : '';
            if ($tag === '') { echo json_encode(0); break; }
            $tag = packingTag($tag);
            $stmt = packingQuery($ConnSL, 'SELECT id FROM MV_BC_TAG WHERE id = ?', array($tag));
            $exists = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            sqlsrv_free_stmt($stmt);
            if (!$exists) throw new Exception('Tag barcode not found');
            $_SESSION['tag_id'] = $tag;
            $user = isset($request['create_by']) ? str_replace("'", "''", trim($request['create_by'])) : '';
            echo json_encode($model->SearchTagDetail($tag, $user));
            break;
        case 'clearTagBarcode':
            $_SESSION['tag_id'] = '';
            echo json_encode(array('success' => true));
            break;
        case 'GetImgTagUpload':
            $filters = array();
            foreach (array('tag_id', 'size_list', 'sts_no', 'job') as $key) $filters[$key] = isset($request[$key]) ? trim($request[$key]) : '';
            $filters['job'] = trim(explode('+', $filters['job'])[0]);
            if (!count(array_filter($filters, function ($value) { return $value !== ''; }))) {
                throw new Exception('กรุณาระบุ Job, Tag, STS NO หรือ Size อย่างน้อย 1 ช่อง');
            }
            echo json_encode($model->GetImgTagUpload($filters['tag_id'], $filters['size_list'], $filters['sts_no'], $filters['job']));
            break;
        case 'updateRemark':
            $tag = packingTag(isset($request['tag_id']) ? trim($request['tag_id']) : '');
            $stmt = packingQuery($ConnSL, 'UPDATE Mv_Bc_Tag_img_upload SET remark = ? WHERE tag_id = ?', array(isset($request['remark']) ? $request['remark'] : '', $tag));
            sqlsrv_free_stmt($stmt);
            echo json_encode(array('success' => true));
            break;
        case 'DeleteImg':
        case 'DeleteTagId':
            $tag = packingTag(isset($request['tag_id']) ? trim($request['tag_id']) : '');
            $slot = $load === 'DeleteImg' ? packingSlot(isset($request['file_name_x']) ? $request['file_name_x'] : '') : null;
            if (!sqlsrv_begin_transaction($ConnSL)) throw new Exception('Cannot start deletion');
            $transaction = true;
            $stmt = packingQuery($ConnSL, 'SELECT file_name_1, file_name_2 FROM Mv_Bc_Tag_img_upload WITH (UPDLOCK, HOLDLOCK) WHERE tag_id = ?', array($tag));
            $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            sqlsrv_free_stmt($stmt);
            if (!$row) throw new Exception('Tag photo record not found');
            foreach ($slot ? array($slot) : array('file_name_1', 'file_name_2') as $field) packingDeleteFile($row[$field]);
            $sql = $slot ? "UPDATE Mv_Bc_Tag_img_upload SET $slot = '' WHERE tag_id = ?" : 'DELETE FROM Mv_Bc_Tag_img_upload WHERE tag_id = ?';
            $stmt = packingQuery($ConnSL, $sql, array($tag));
            sqlsrv_free_stmt($stmt);
            if (!sqlsrv_commit($ConnSL)) throw new Exception('Cannot save deletion');
            $transaction = false;
            echo json_encode(array('success' => true));
            break;
        default:
            http_response_code(400);
            echo json_encode(array('success' => false, 'message' => 'Unsupported action'));
    }
} catch (Exception $e) {
    if ($transaction) sqlsrv_rollback($ConnSL);
    http_response_code(400);
    echo json_encode(array('success' => false, 'message' => $e->getMessage()));
}
