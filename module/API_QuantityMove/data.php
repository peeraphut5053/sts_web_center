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
if ($load == "SearchTagDetail") {
    $mv_bc_tag = $CallModelObj->SearchTagDetail($tag_id);
    echo json_encode($mv_bc_tag);
}
if ($load == "SearchTagDetailCheckByDO") {
    $mv_bc_tag = $CallModelObj->SearchTagDetailCheckByDO($tag_id,$do_num);
    echo json_encode($mv_bc_tag);
}

if ($load == "location_mst") {
    $location_mst = $CallModelObj->location_mst();
    echo json_encode($location_mst);
}

if ($load == "wc_mst") {
    $wc_mst = $CallModelObj->wc_mst();
    echo json_encode($wc_mst);
}

if ($load == "item_mst") {
    $location_mst = $CallModelObj->item_mst();
    echo json_encode($location_mst);
}

if ($load == "itemwhse_mst") {
    $location_mst = $CallModelObj->item_mst();
    echo json_encode($location_mst);
}

if ($load == "STS_qty_move_hrd") {
    $STS_qty_move_hrd = $CallModelObj->STS_qty_move_hrd();
    echo json_encode($STS_qty_move_hrd);
}

if ($load == "STS_qty_move_line") {
    $STS_qty_move_line = $CallModelObj->STS_qty_move_line($doc_num);
    echo json_encode($STS_qty_move_line);
}

if ($load == "SelectDOList") {
    $STS_qty_move_line = $CallModelObj->SelectDOList($do_num);
    echo json_encode($STS_qty_move_line);
}

if ($load == "moveqty_create_hdr") {
    (isset($w_c)) ? $w_c = $w_c : $w_c = "";
    (isset($destination)) ? $destination = $destination : $destination = "";
	(isset($ActWeight)) ? $ActWeight = $ActWeight : $ActWeight = 0;

    //
    (isset($doc_type)) ? $doc_type = $doc_type : $doc_type = "Internal";
    (isset($do_num)) ? $do_num = $do_num : $do_num = "";
    (isset($boatList)) ? $boatList = $boatList : $boatList = "";

    $moveqty_create_hdr = $CallModelObj->moveqty_create_hdr($toLoc, $w_c, $doc_type, $do_num, $boatList, $destination, $ActWeight);
    echo json_encode($moveqty_create_hdr);
}

if ($load == "moveqty_create_line") {
    $CallModelObj->moveqty_create_line($docnum, $docline, $tagnum, $toLoc, $boatPosition);
}

if ($load == "checkItemLotLoc") {
    $checkItemLotLoc = $CallModelObj->checkItemLotLoc($loc);
    echo json_encode($checkItemLotLoc);
}

if ($load == "update_doc_hdr") {
    $checkItemLotLoc = $CallModelObj->update_doc_hdr();
    echo json_encode($checkItemLotLoc);
}

if ($load == "DairyReportCountLotByWC") {
    $checkItemLotLoc = $CallModelObj->DairyReportCountLotByWC();
    echo json_encode($checkItemLotLoc);
}

if ($load == "DairyReportCountLotByWCDate") {
    $checkItemLotLoc = $CallModelObj->DairyReportCountLotByWCDate($w_c);
    echo json_encode($checkItemLotLoc);
}

if ($load == "DairyReportLine") {
    $checkItemLotLoc = $CallModelObj->DairyReportLine($w_c, $date);
    echo json_encode($checkItemLotLoc);
}

if ($load == "STS_list_of_do_group") {
    $checkItemLotLoc = $CallModelObj->STS_list_of_do_group();
    echo json_encode($checkItemLotLoc);
}


if ($load == "InsertSTS_list_of_do_group") {
    $checkItemLotLoc = $CallModelObj->InsertSTS_list_of_do_group($do_group_num,$do_group_name,$do_group_list,$do_group_status);
    echo json_encode($checkItemLotLoc);
}

if ($load == "UpdateSTS_list_of_do_group") {
    $checkItemLotLoc = $CallModelObj->UpdateSTS_list_of_do_group($id,$do_group_num,$do_group_name,$do_group_list,$do_group_status);
    echo json_encode($checkItemLotLoc);
}

if ($load == "DeleteSTS_list_of_do_group") {
    $checkItemLotLoc = $CallModelObj->DeleteSTS_list_of_do_group($id);
    echo json_encode($checkItemLotLoc);
}

if ($load == "UpdateBoat_Position") {
    $checkItemLotLoc = $CallModelObj->UpdateBoat_Position($id,$doc_num,$boat_position);
    echo json_encode($checkItemLotLoc);
}

if ($load == "Search_STS_qty_move_hrd") {
    $Search_STS_qty_move_hrd = $CallModelObj->Search_STS_qty_move_hrd($doc_num);
    echo json_encode($Search_STS_qty_move_hrd);
}

if ($load == "editActualWeight") {
    $editActualWeight = $CallModelObj->editActualWeight($doc_num, $eActualWeight);
    echo json_encode($editActualWeight);
}

