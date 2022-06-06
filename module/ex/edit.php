<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
$temp->setReplace("{crumb}", "");
$temp->setReplace("{pagename}", "Edit");
$temp->setReplace("{content}", $temp->getTemplate("./template/edit.html"));
$HeadCode = "";
$selMV = 0 ;
$selBA = 0 ;
!empty($_GET["selMV"]) ? $selMV = $_GET["selMV"] : $selMV = 0 ;
!empty($_GET["selBA"]) ? $selBA = $_GET["selBA"] : $selBA = 0 ;

if (!empty($_GET["HeadCode"])) {
    $HeadCode = $_GET["HeadCode"];
} else {
    if(isset($_POST["HeadCode"])){
        $HeadCode = $_POST["HeadCode"];
    }
    
}
/// Head Object ///
$BoatPlanHead = new BoatPlanHead();
$BoatPlanHead->setConn($conn);
$BoatPlanHead->_HeadCode = $HeadCode ;
$BoatPlanHead->GetProperty();
///Lists object ////
$BoatPlanList= new BoatPlanList();
$BoatPlanList->setConn($conn);
$BoatPlanList->_HeadCode = $HeadCode ;
$resultList = $BoatPlanList->GetRowsWithHeader() ;

$Boat = new Boat();
$Boat->setConn($conn);
if($selMV !=""){
      $Boat->_IdRun = $selMV ;
      $Boat->GetProperty();
      $LighterNo = $Boat->Boat_LighterNo;
    $ddlMV = $Boat->PopulateAll( 0 , $selMV);
    
    
}else{
    $ddlMV = $Boat->PopulateAll( 0 , $BoatPlanHead->Boat_Id);
    $LighterNo = $BoatPlanHead->Boat_LighterNo;
}

//Populate Harbor list
$Harbor = new Harbor();
$Harbor->setConn($conn);
$HN= "" ;
if($selBA !=""){
  
    $ddlBA = $Harbor->Populate( "HB_Status = 2 OR  HB_Status = 0 ",0 , $selBA);
}else{
    $ddlBA = $Harbor->Populate( "HB_Status = 2 OR  HB_Status = 0 ",0 , $BoatPlanHead->Berthed_Id);
}

//$ddl1= $Harbor->Populate("HB_Status = 2 OR  HB_Status = 0 ",0, $BoatPlanHead->Berthed_Id);
//Populate Position list
$Pos = new BoatPosition();
$Pos->setConn($conn);
//$HN= "" ;

//$ddl2 = $Harbor->Populate("HB_Status = 2 OR  HB_Status = 1 ",0, 0);


$docno = $BoatPlanHead->HeadCode;
$MV = $BoatPlanHead->Boat_MV;

$BerthedAt = $BoatPlanHead->HB_Name;
$StartDate = $BoatPlanHead->StartDate;
//$CompleteDate = $BoatPlanHead->CompleteDate ;
$lists = "";
$CountItem =count($resultList) ;
if($CountItem >= 1 ){
    foreach ($resultList as $index => $rows) {
        $ddlPos= $Pos->PopulateAll(0,$rows["itemPosition"] );        
    $lists = $lists . '<div class="row " > <input type="hidden" name="hdfId[]"  id="hdfId[]" value="' . $rows["IdRun"] . '">'
            . '<input type="hidden" name="hdItemPos[]" value="'.$rows["itemPosition"] .'">  ';
    $lists = $lists . ' <div class="col-2 text-center col-in"> ';
    $lists = $lists . '<input class="form-control" name="txtItemPos[]" value="'.$rows["position"] .'" readonly>';
    $lists = $lists . '</div>';
    $lists = $lists . ' <div class="col-3 text-center col-in" id="colclone'.$index.'">';
    $lists = $lists . '   <input class="form-control" name="txtItemTag[]"  id="txtItemTag[]" value="' . $rows["itemTag"] . '" readonly>';
    $lists = $lists . ' </div>';
         $lists = $lists . ' <div class="col-3 text-center col-in" id="colclone0">';
    $lists = $lists . '   <input class="form-control" name="txtCoNum[]"  readonly id="txtCoNum[]" value="">';
    $lists = $lists . ' </div>';
    $lists = $lists . ' <div class="col-2 text-center col-in">';
    $lists = $lists . '     <input class="form-control" name="txtDescription[]"  readonly id="txtDescription[]" value="' . $rows["item"] . '" readonly> ';
    $lists = $lists . '  </div>';
    $lists = $lists . '   <div class="col-1 text-center col-in" style="padding-left:2px;">';
    $lists = $lists . '     <input class="form-control number" name="itemQTY[]" onkeypress="return floatOnly(event);" id="itemQTY[]" value="' . $rows["qty1"] . '" readonly>';
    $lists = $lists . ' </div>';
     $lists = $lists .'<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col-1 text-center col-in"><a href="javascript:void(0);" id="remCF" class="btn btn-danger btn-del-mini btn-round"><i class="fa fa-close"></i></a> </div> ';
    $lists = $lists . ' </div>';
}
}else{
       $ddlPos= $Pos->PopulateAll(0,0);        
    $lists = $lists . '<div class="row " > <input type="hidden" name="hdfId[]"  id="hdfId[]" value="">';
    $lists = $lists . ' <div class="col-2 text-center col-in"> ';
    $lists = $lists . '<select class="form-control" name="selPosition[]" id="selPosition">'.$ddlPos.'</select>';
    $lists = $lists . '</div>';
    $lists = $lists . ' <div class="col-3 text-center col-in" id="colclone0">';
    $lists = $lists . '   <input class="form-control" name="txtItemTag[]"  id="txtItemTag[]" value="">';
    $lists = $lists . ' </div>';
     $lists = $lists . ' <div class="col-3 text-center col-in" id="colclone0">';
    $lists = $lists . '   <input class="form-control" name="txtCoNum[]" readonly id="txtCoNum[]" value="">';
    $lists = $lists . ' </div>';
    $lists = $lists . ' <div class="col-2 text-center col-in">';
    $lists = $lists . '     <input class="form-control" name="txtDescription[]" id="txtDescription[]" value=""> readonly';
    $lists = $lists . '  </div>';
    $lists = $lists . '   <div class="col-1 text-center col-in" style="padding-left:2px;">';
    $lists = $lists . '     <input class="form-control number" name="itemQTY[]" onkeypress="return floatOnly(event);" id="itemQTY[]" value="" readonly>';
    $lists = $lists . ' </div>';
     $lists = $lists .'<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col-1 text-center col-in"><a href="javascript:void(0);" id="remCF" class="btn btn-danger btn-del-mini btn-round"><i class="fa fa-close"></i></a> </div> ';
    $lists = $lists . ' </div>';
//     $lists = $lists .'<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col-1 text-center col-in"><a href="javascript:void(0);" id="remCF" class="btn btn-danger btn-del-mini btn-round"><i class="fa fa-close"></i></a> </div> ';
//    $lists = $lists . ' </div>';
}

$temp->setReplace("{docno}", $docno);
$temp->setReplace("{txtMv}", $MV);
$temp->setReplace("{countItem}", $CountItem);
$temp->setReplace("{MVLists}", $ddlMV);
$temp->setReplace("{BALists}", $ddlBA);
//$temp->setReplace("{BAList2}", $ddl2);
$temp->setReplace("{txtLighterNo}", $LighterNo);
$temp->setReplace("{txtBerthedAt}", $BerthedAt);
$temp->setReplace("{StartDate}", $StartDate);
//$temp->setReplace("{CPDate}", $CompleteDate);
$temp->setReplace("{lists}", $lists);
