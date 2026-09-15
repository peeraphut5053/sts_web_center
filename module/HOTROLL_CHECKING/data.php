<?php

header("Access-Control-Allow-Origin: *");
while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../../initial.php";


if ($load == 'ajax') {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $po_QC = new QcTestLab();
    $po_QC->setConn($ConnSL);
    $where = "where 1=1";
    if (($txtFromDate != "") && ($txtToDate != "")) {
        $where .= " AND ( CONVERT(date, qc.po_date) BETWEEN '$txtFromDate' AND '$txtToDate' ) ";
    } else if ($txtFromDate != "") {
        $where .= " AND CONVERT(date, qc.po_date) >= '$txtFromDate' ";
    } else if ($txtToDate != "") {
        $where .= " AND CONVERT(date, qc.po_date) <= '$txtToDate' ";
    }

    if (($from_stsno != "") && ($to_stsno != "")) {
        if (is_numeric($from_stsno) && is_numeric($to_stsno)) {
            $from_num = intval($from_stsno);
            $to_num = intval($to_stsno);
            $where .= " AND (
                (TRY_CAST(qc.sno AS BIGINT) BETWEEN $from_num AND $to_num)
                OR (TRY_CAST(qc.sts_no AS BIGINT) BETWEEN $from_num AND $to_num)
                OR (qc.sno BETWEEN '$from_stsno' AND '$to_stsno')
                OR (qc.sts_no BETWEEN '$from_stsno' AND '$to_stsno')
            ) ";
        } else {
            $where .= " AND (
                (qc.sno BETWEEN '$from_stsno' AND '$to_stsno')
                OR (qc.sts_no BETWEEN '$from_stsno' AND '$to_stsno')
            ) ";
        }
    } else if ($from_stsno != "") {
        if (is_numeric($from_stsno)) {
            $from_num = intval($from_stsno);
            $where .= " AND (
                (TRY_CAST(qc.sno AS BIGINT) >= $from_num)
                OR (TRY_CAST(qc.sts_no AS BIGINT) >= $from_num)
                OR (qc.sno >= '$from_stsno')
                OR (qc.sts_no >= '$from_stsno')
            ) ";
        } else {
            $where .= " AND (qc.sno >= '$from_stsno' OR qc.sts_no >= '$from_stsno') ";
        }
    } else if ($to_stsno != "") {
        if (is_numeric($to_stsno)) {
            $to_num = intval($to_stsno);
            $where .= " AND (
                (TRY_CAST(qc.sno AS BIGINT) <= $to_num)
                OR (TRY_CAST(qc.sts_no AS BIGINT) <= $to_num)
                OR (qc.sno <= '$to_stsno')
                OR (qc.sts_no <= '$to_stsno')
            ) ";
        } else {
            $where .= " AND (qc.sno <= '$to_stsno' OR qc.sts_no <= '$to_stsno') ";
        }
    }

    if ($sno != "") {
        $where .= " AND (qc.sno like '%" . $sno . "%' OR qc.sts_no like '%" . $sno . "%') ";
    }
    if ($c_no != "") {
        $where .= " AND qc.c_no like '%" . $c_no . "%' ";
    }
    if ($h_no != "") {
        $where .= " AND qc.h_no like '%" . $h_no . "%' ";
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





