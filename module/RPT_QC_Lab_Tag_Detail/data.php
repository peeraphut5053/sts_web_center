<?php
header("Access-Control-Allow-Origin: *");

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

include "../../initial.php";

if ($load == "ajax") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Mv_Bc_Tag = new BcTag();
    $Mv_Bc_Tag->setConn($ConnSL);
    $rs = $Mv_Bc_Tag->QcLabTagDetail($tag_id, $sts_no, $StartDate, $EndDate);
    echo json_encode($rs);
} elseif ($load == 'grape') {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Mv_Bc_Tag = new BcTag();
    $Mv_Bc_Tag->setConn($ConnSL);
    $rs = $Mv_Bc_Tag->getDataChart($StartDate, $EndDate);
    echo json_encode($rs);
} elseif ($load == 'group') {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Mv_Bc_Tag = new BcTag();
    $Mv_Bc_Tag->setConn($ConnSL);
    $rs = $Mv_Bc_Tag->getGroupChart($StartDate, $EndDate, $GroupBy);
    echo json_encode($rs);
} elseif ($load == 'work') {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Mv_Bc_Tag = new BcTag();
    $Mv_Bc_Tag->setConn($ConnSL);
    $rs = $Mv_Bc_Tag->getWorkCenters($StartDate, $EndDate);
    echo json_encode($rs);
} elseif ($load == 'group2') {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Mv_Bc_Tag = new BcTag();
    $Mv_Bc_Tag->setConn($ConnSL);
    $rs = $Mv_Bc_Tag->getGroupChart2($StartDate, $EndDate, $StartLastMonth, $EndLastMonth, $GroupBy);
    echo json_encode($rs);
} elseif ($load == 'DailyReport') {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Mv_Bc_Tag = new BcTag();
    $Mv_Bc_Tag->setConn($ConnSL);
    $rs = $Mv_Bc_Tag->getDailyReport($date,$type);
    echo json_encode($rs);
} elseif ($load == 'DailyWorkCenter') {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Mv_Bc_Tag = new BcTag();
    $Mv_Bc_Tag->setConn($ConnSL);
    $rs = $Mv_Bc_Tag->getDailyWorkCenter($StartDate, $EndDate, $wc,$type);
    echo json_encode($rs);
} elseif ($load == 'paretoData') {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Mv_Bc_Tag = new BcTag();
    $Mv_Bc_Tag->setConn($ConnSL);
    $rs = $Mv_Bc_Tag->getParetoData($StartDate, $EndDate);
    echo json_encode($rs);
} elseif ($load == 'paretoData2') {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Mv_Bc_Tag = new BcTag();
    $Mv_Bc_Tag->setConn($ConnSL);
    $rs = $Mv_Bc_Tag->getParetoDataFinishing($StartDate, $EndDate,$GroupBy);
    echo json_encode($rs);
} elseif ($load == 'TableDetail') {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Mv_Bc_Tag = new BcTag();
    $Mv_Bc_Tag->setConn($ConnSL);
    $rs = $Mv_Bc_Tag->getTableDataGroup($StartDate, $EndDate, $wc);
    echo json_encode($rs);
} elseif ($load == 'status') {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Mv_Bc_Tag = new BcTag();
    $Mv_Bc_Tag->setConn($ConnSL);
    $rs = $Mv_Bc_Tag->getWorkCentersStatus($date, $wc);
    echo json_encode($rs);
} elseif ($load == 'SaveStatus') {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Mv_Bc_Tag = new BcTag();
    $Mv_Bc_Tag->setConn($ConnSL);
    $rs = $Mv_Bc_Tag->SaveWorkCentersStatus($wc);
    echo json_encode($rs);
} elseif ($load == 'SaveClose') {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Mv_Bc_Tag = new BcTag();
    $Mv_Bc_Tag->setConn($ConnSL);
    $rs = $Mv_Bc_Tag->SaveWorkCentersStatusClose($id);
    echo json_encode($rs);
} elseif ($load == 'SaveCancel') {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Mv_Bc_Tag = new BcTag();
    $Mv_Bc_Tag->setConn($ConnSL);
    $rs = $Mv_Bc_Tag->SaveWorkCentersStatusCancel($id);
    echo json_encode($rs);
} else {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Mv_Bc_Tag = new BcTag();
    $Mv_Bc_Tag->setConn($ConnSL);
    $rs = $Mv_Bc_Tag->SaveQc_Oper_Num($tag_id, $qc_oper_num);
    echo json_encode($rs);
}