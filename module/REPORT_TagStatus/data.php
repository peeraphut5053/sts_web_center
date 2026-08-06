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
    
    if (isset($item) AND $item != '') $wh .= " AND item.item LIKE '".$item."%'";
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

if ($load == "update_issue") {
    $mv_bc_tag = $CallModelObj->UpdateIssue($id, $issue_value);
    echo json_encode($mv_bc_tag);
}

if ($load == "update_minor") {
    $mv_bc_tag = $CallModelObj->UpdateMinor($id, $minor_value);
    echo json_encode($mv_bc_tag);
}

if ($load == "update_main") {
    $mv_bc_tag = $CallModelObj->UpdateMain($id, $main_value);
    echo json_encode($mv_bc_tag);
}

if ($load == "save_nc") {
    $mv_bc_tag = $CallModelObj->UpdateNC($id, $nc_value);
    echo json_encode($mv_bc_tag);
}

if ($load == "save_record_date") {
    $mv_bc_tag = $CallModelObj->UpdateRecordDate($id, $record_date);
    echo json_encode($mv_bc_tag);
}

if ($load == "save_qc_loc") {
    $mv_bc_tag = $CallModelObj->SaveQCLoc($id, $qc_loc_value);
    echo json_encode($mv_bc_tag);
}

if ($load == "save_qc_source") {
    $mv_bc_tag = $CallModelObj->SaveQCSource($id, $qc_source_value);
    echo json_encode($mv_bc_tag);
}

if ($load == "save_qc_mistake") {
    $mv_bc_tag = $CallModelObj->SaveQCMistake($id, $qc_mistake_value);
    echo json_encode($mv_bc_tag);
}

if ($load == "GetReportFrequency") {
    $year = isset($_GET['year']) ? $_GET['year'] : (isset($_POST['year']) ? $_POST['year'] : 2026);
    $mv_bc_tag = $CallModelObj->GetReportFrequency($year);
    echo json_encode($mv_bc_tag);
}


