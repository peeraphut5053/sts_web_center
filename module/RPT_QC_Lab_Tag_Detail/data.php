<?php
header("Access-Control-Allow-Origin: *");
while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
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
    $rs = $Mv_Bc_Tag->getDataChart($StartDate, $EndDate, $StartLastMonth, $EndLastMonth);
    echo json_encode($rs);
} elseif ($load == 'group') {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Mv_Bc_Tag = new BcTag();
    $Mv_Bc_Tag->setConn($ConnSL);
    $rs = $Mv_Bc_Tag->getGroupChart($StartDate, $EndDate, $StartLastMonth, $EndLastMonth,$GroupBy);
    echo json_encode($rs);
} else {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Mv_Bc_Tag = new BcTag();
    $Mv_Bc_Tag->setConn($ConnSL);
    $rs = $Mv_Bc_Tag->SaveQc_Oper_Num($tag_id, $qc_oper_num);
    echo json_encode($rs);
}