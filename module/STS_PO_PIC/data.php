<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json; charset=utf-8');

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

include "../../initial.php";

$CallModel = new CallModel();
$CallModel->SyteLine_Models();
$PO = new PurchaseOrder();
$PO->setConn($ConnSL);

$load = isset($load) ? $load : '';

if ($load == "SearchPO") {
    $StartDate = isset($StartDate) ? $StartDate : date('Y-m-01');
    $EndDate = isset($EndDate) ? $EndDate : date('Y-m-d');
    $po_num = isset($po_num) ? $po_num : '';
    $vend = isset($vend) ? $vend : '';
    $file_status = isset($file_status) ? $file_status : 'ALL';
    $pr_num = isset($pr_num) ? $pr_num : '';

    $rs = $PO->GetPoCompareList($StartDate, $EndDate, $po_num, $vend, $file_status, $pr_num);
    echo json_encode($rs);
} else if ($load == "ViewFile") {
    $file = isset($file) ? basename($file) : (isset($file_name) ? basename($file_name) : '');
    if (empty($file) || !preg_match('/\.pdf$/i', $file)) {
        header("HTTP/1.0 404 Not Found");
        echo "File not found";
        exit;
    }

    // Stream directly from SeaweedFS (NAS Distributed Object Storage)
    if (SeaweedFS::stream('po-pic', $file, 'application/pdf')) {
        exit;
    }

    header("HTTP/1.0 404 Not Found");
    echo "ไม่พบไฟล์ PDF ใน SeaweedFS";
    exit;
} else if ($load == "DeleteFile") {
    $po_num = isset($po_num) ? $po_num : '';
    $file_name = isset($file_name) ? $file_name : (isset($file) ? $file : '');
    if (empty($po_num)) {
        echo json_encode(array("success" => false, "message" => "ไม่พบเลขที่ PO"));
        exit;
    }

    $res = $PO->DeletePoPic($po_num, $file_name);
    if ($res['success']) {
        $filesToDelete = !empty($res['files']) ? $res['files'] : (!empty($res['file']) ? array($res['file']) : array());
        foreach ($filesToDelete as $f) {
            if (!empty($f)) {
                SeaweedFS::delete('po-pic', basename($f));
            }
        }
        echo json_encode(array("success" => true, "message" => "ลบไฟล์สำเร็จ"));
    } else {
        echo json_encode(array("success" => false, "message" => "เกิดข้อผิดพลาดในการลบข้อมูล"));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Invalid action"));
}
