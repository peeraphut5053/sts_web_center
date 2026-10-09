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
} else if ($load == "DeleteFile") {
    $po_num = isset($po_num) ? $po_num : '';
    if (empty($po_num)) {
        echo json_encode(array("success" => false, "message" => "ไม่พบเลขที่ PO"));
        exit;
    }

    $res = $PO->DeletePoPic($po_num);
    if ($res['success']) {
        if (!empty($res['file'])) {
            $filePath = __DIR__ . "/files/" . $res['file'];
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }
        echo json_encode(array("success" => true, "message" => "ลบไฟล์สำเร็จ"));
    } else {
        echo json_encode(array("success" => false, "message" => "เกิดข้อผิดพลาดในการลบข้อมูล"));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Invalid action"));
}
