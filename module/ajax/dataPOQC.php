<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}
include "./initial.php";
//
$po_QC = new PO_QC();
$po_QC->setConn($var);
$ret = "";
//$action = $_POST["action"];

if ($action == "Search") {
    $GetTable = $po_QC->Ajax_GetRowsWithDate($startDate, $endDate, $status, $filterRefNo, $filterStsNo, $searchType);
    $arrReturn = array();
    $btnPrintTag = "";
    echo json_encode($GetTable);
} else if ($action == "SearchLine") {

    $GetTable = $po_QC->SearchLine($sno);
    $arrReturn = array();
    $btnPrintTag = "";
    echo json_encode($GetTable);
} else if ($action == "ClearGrn") {
    $GetTable = $po_QC->ClearGrn($sno);

    $query = "INSERT INTO Log_Clear_MatchGrn (date_clear , grn_num , po_num , po_line , item , sno) VALUES (GETDATE(), '$grn_num' , '$po_num' , '$po_line' , '$item' , '$sno') ";
    $cSql = new SqlSrv();
    $rs0 = $cSql->IsUpDel($conn_webapp, $query);
    return $rs0 ;
    
    
}
?>
