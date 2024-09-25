<?php

// 
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


if ($load == 'ajax') {
    $CallModel = new CallModel();
    $CallModel->MGT_Models();
    $po_QC = new PO_QC();
    $po_QC->setConn($var);
    $where = "where 1=1";
    if (($txtFromDate != "") && ($txtToDate != "")) {
        $where = $where . " AND ( po_date BETWEEN '$txtFromDate' AND '$txtToDate' )  ";
    }
    if ($sno != "") {
        $where = $where . " AND sno like '%" . $sno . "%' ";
    }
    if ($c_no != "") {
        $where = $where . " AND c_no like '%" . $c_no . "%' ";
    }
    if ($h_no != "") {
        $where = $where . " AND h_no = '" . $h_no . "' ";
    }
    $po_QC = $po_QC->GetRowsWithCond2($where);
    $CallModel = null;
//    $CallModel2 = new CallModel();
//    $CallModel2->SyteLine_Models();
//    $CallModelObj = new po_qc_sl();
//    $CallModelObj->setConn($ConnSL);
//    $CallModelObj->InsertSts_po_qc($po_QC);
    echo json_encode($po_QC);
} else if ($load == 'tblReportDetail') {
    $CallModel = new CallModel();
    $CallModel->MGT_Models();
    $po_QC = new PO_QC();
    $po_QC->setConn($var);
    $where = "where 1=1";

    if ($sno != "") {
        $where = $where . " AND sno = '" . $sno . "' ";
    }

    $po_QC = $po_QC->tblReportDetail($where);
    $CallModel = null;
    echo json_encode($po_QC);
} else if ($load == 'ToggleAction') {
    $CallModel = new CallModel();
    $CallModel->MGT_Models();
    $po_QC = new PO_QC();
    $po_QC->setConn($var);
    $po_QC = $po_QC->UpdateCheckHeatNoStatus($sts_no, $h_no, $thick, $width, $CurrAction);
    echo json_encode($po_QC);
} else if ($load == 'insert_product_test') {
    $CallModel = new CallModel();
    $CallModel->MGT_Models();
    $po_QC = new PO_QC();
    $po_QC->setConn($var);
    $po_QC = $po_QC->insert_product_test($sno);
    echo json_encode($po_QC);
} else if ($load == 'UpdateQcTestLab') {
    $CallModel = new CallModel();
    $CallModel->MGT_Models();
    $UpdateQcTestLab = new PO_QC();
    $UpdateQcTestLab->setConn($var);
    $UpdateQcTestLab = $UpdateQcTestLab->UpdateQcTestLab($sno, $col_name, $valdata);
    echo json_encode($sno);
} else if ($load == 'update_product_test') {
    $CallModel = new CallModel();
    $CallModel->MGT_Models();
    $UpdateQcTestLab = new PO_QC();
    $UpdateQcTestLab->setConn($var);
    $UpdateQcTestLab = $UpdateQcTestLab->update_product_test($id, $col_name, $valdata);
    echo json_encode($id);
} else if ($load == 'delete_product_test') {
    $CallModel = new CallModel();
    $CallModel->MGT_Models();
    $UpdateQcTestLab = new PO_QC();
    $UpdateQcTestLab->setConn($var);
    $UpdateQcTestLab = $UpdateQcTestLab->delete_product_test($id, $sno);
    echo json_encode($sno);
}





