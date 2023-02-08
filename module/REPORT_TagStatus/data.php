<?php

header("Access-Control-Allow-Origin: *");
while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
require_once "../initial.php";
$CallModel = new CallModel();
$CallModel->SyteLine_Models();
$CallModelObj = new BcTag();
$CallModelObj->setConn($ConnSL);
if ($load == "SearchTagStatus") {
    if (isset($tag_id) AND $tag_id != '') {
        $wh = " and id LIKE '".$tag_id."%'";
    } else {
        $wh = "";
    }
    if (isset($sts_no) AND $sts_no != '') $wh .= " and sts_no LIKE '".$sts_no."%'";
    else $sts_no = "";
    
    if (isset($job) AND $job != '') $wh .= " and job LIKE '".$job."%'";
    else $job = "";
    
    if (isset($item) AND $item != '') $wh .= " and item LIKE '".$item."%'";
    else $item = "";
    
    if (isset($lot) AND $lot != '') $wh .= " and lot LIKE '".$lot."%'";
    else $lot = "";
    
    if (isset($tag_status ) AND $tag_status  != '') $wh .= " and tag_status LIKE '".$tag_status."%'";
    else $tag_status  = "";
    
    if (isset($mfg_date ) AND $mfg_date  != '') $wh .= " and mfg_date between '".($mfg_date)." 00:00:00.000' and  '".($mfg_date)." 23:59:59.000'";
    else $mfg_date  = "";
    // if (!$wh) $sql = "";
    $mv_bc_tag = $CallModelObj->SearchTagStatus($wh);
    echo json_encode($mv_bc_tag);
}

if ($load == "update_status") {
    $mv_bc_tag = $CallModelObj->UpdateTagStatus($id,$status_value);
    echo json_encode($mv_bc_tag);
}

if ($load == "save_detail") {
    $mv_bc_tag = $CallModelObj->SaveDetail($detail_id,$detail_value);
    echo json_encode($mv_bc_tag);
}