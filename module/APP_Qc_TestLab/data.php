<?php

// 
while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
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
        $where = $where . " AND sno = '" . $sno . "' ";
    }
    if ($c_no != "") {
        $where = $where . " AND c_no = '" . $c_no . "' ";
    }
    if ($h_no != "") {
        $where = $where . " AND h_no = '" . $h_no . "' ";
    }
    $po_QC = $po_QC->GetRowsWithCond2($where);
    $CallModel = null;
    echo json_encode($po_QC);
}else if ($load == 'tblReportDetail') {
    $CallModel = new CallModel();
    $CallModel->MGT_Models();
    $po_QC = new PO_QC();
    $po_QC->setConn($var);
    $where = "where 1=1";
    if ($sts_no != "") {
        $where = $where . " AND sts_no = '" . $sts_no . "' ";
    }
    $po_QC = $po_QC->tblReportDetail($where);
    $CallModel = null;
    echo json_encode($po_QC);
}


if ($load == 'UpdateQcTestLab') {
    $CallModel = new CallModel();
    $CallModel->MGT_Models();
    $UpdateQcTestLab = new PO_QC();
    $UpdateQcTestLab->setConn($var);
    $UpdateQcTestLab = $UpdateQcTestLab->UpdateQcTestLab($sts_no,$col_name,$valdata);
    echo json_encode($sts_no);
}





