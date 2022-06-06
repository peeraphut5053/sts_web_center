<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
$temp->setReplace("{crumb}", "");
$temp->setReplace("{pagename}", "Edit");
$temp->setReplace("{content}", $temp->getTemplate("./template/bn_edit.html"));
$HeadCode = "";
if (!empty($_GET["HeadCode"])) {
    $HeadCode = $_GET["HeadCode"];
} else {
    $HeadCode = $_POST["HeadCode"];
}

$selMV = 0 ;
$selBA = 0 ;
!empty($_GET["selMV"]) ? $selMV = $_GET["selMV"] : $selMV = 0 ;
!empty($_GET["selBA"]) ? $selBA = $_GET["selBA"] : $selBA = 0 ;

/// Head Object ///
$BoatNoteHead = new BoatNoteHead();
$BoatNoteHead->setConn($conn);
$BoatNoteHead->_HeadCode = $HeadCode ;
$BoatNoteHead->GetProperty();
///Lists object ////
$BoatNoteList= new BoatNoteList();
$BoatNoteList->setConn($conn);
$BoatNoteList->_HeadCode = $HeadCode ;
$resultList = $BoatNoteList->GetRowsWithHeader() ;
//Connect Object
//$cSql = new SqlSrv();

//if(!empty($_GET["selMV"])){
//  $Boat->_IdRun = $_GET["selMV"] ;
//  $Boat->GetProperty();
//  $LN=$Boat->Ship_LighterNo ;
//  $Selected =$_GET["selMV"] ;
//}
$Boat = new Boat();
$Boat->setConn($conn);

if($selMV !=""){
      $Boat->_IdRun = $selMV ;
      $Boat->GetProperty();
      $LighterNo = $Boat->Ship_LighterNo;
    $ddlMV = $Boat->PopulateAll( 0 , $selMV);
    
    
}else{
    $ddlMV = $Boat->PopulateAll( 0 , $BoatNoteHead->Boat_Id);
    $LighterNo = $BoatNoteHead->Ship_LighterNo;
}


//Populate Harbor list
$Harbor = new Harbor();
$Harbor->setConn($conn);
$HN= "" ;


$ddl1= $Harbor->Populate("HB_Status = 2 OR  HB_Status = 0 ",0, $BoatNoteHead->Berthed_Id);



$docno = $BoatNoteHead->HeadCode;
$MV = $BoatNoteHead->Ship_MV;
//$LighterNo = $BoatNoteHead->Ship_LighterNo;
$BerthedAt = $BoatNoteHead->HB_Name;
$CommencedDate = $BoatNoteHead->CommencedDate;
$CompleteDate = $BoatNoteHead->CompleteDate ;
$lists = "";
$CountItem =count($resultList) ;
//$tmpDDlShipTo = 0;
if($CountItem >= 1 ){
    foreach ($resultList as $index => $rows) {
        $ddl2 = $Harbor->Populate("HB_Status = 2 OR  HB_Status = 1 ",0, $rows["ShipTo"]);
    $lists = $lists . '<div class="row " > <input type="hidden" name="hdfId[]"  id="hdfId[]" value="' . $rows["IdRun"] . '">';
    $lists = $lists . ' <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-2 text-center col-in"> ';
    $lists = $lists . '<input class="form-control" name="txtLotNo[]"  id="txtLotNo[]" value="' . $rows["LotNo"] . '">';
    $lists = $lists . '</div>';
    $lists = $lists . ' <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-2 text-center col-in" id="colclone'.$index.'">';
    $lists = $lists . '   <select class="form-control" name="selTO[]" id="selTO">'.$ddl2.'</select>';
    $lists = $lists . ' </div>';
    $lists = $lists . ' <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-2 text-center col-in">';
    $lists = $lists . '     <input class="form-control number" name="txtBundles[]" onkeypress="return floatOnly(event);"  id="txtBundles[]" value="' . $rows["Bundles"] . '">';
    $lists = $lists . '  </div>';
    $lists = $lists . ' <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-2 text-center col-in">';
    $lists = $lists . '     <input class="form-control" name="txtDescription[]" id="txtDescription[]" value="' . $rows["Description"] . '">';
    $lists = $lists . '  </div>';
    $lists = $lists . '   <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-3 text-center col-in" style="padding-left:2px;">';
    $lists = $lists . '     <input class="form-control number" name="GrossWeight[]" onkeypress="return floatOnly(event);" id="GrossWeight[]" value="' . $rows["GrossWeight"] . '">';
    $lists = $lists . ' </div>';
     $lists = $lists .'<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col-1 text-center col-in"><a href="javascript:void(0);" id="remCF" class="btn btn-danger btn-del-mini btn-round"><i class="fa fa-close"></i></a> </div> ';
    $lists = $lists . ' </div>';
}
}else{
     $lists = $lists . '<div class="row " > <input type="hidden" name="hdfId[]"  id="hdfId[]" value="">';
    $lists = $lists . ' <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-2 text-center col-in"> ';
    $lists = $lists . '<input class="form-control" name="txtLotNo[]"  id="txtLotNo[]" value="">';
    $lists = $lists . '</div>';
    $lists = $lists . ' <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-2 text-center col-in" id="colclone0">';
    $lists = $lists . '   <select class="form-control" name="selTO[]" id="selTO">'.$ddl2.'</select>';
    $lists = $lists . ' </div>';
    $lists = $lists . ' <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-2 text-center col-in">';
    $lists = $lists . '     <input class="form-control number" name="txtBundles[]" onkeypress="return floatOnly(event);"  id="txtBundles[]" value="">';
    $lists = $lists . '  </div>';
    $lists = $lists . ' <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-2 text-center col-in">';
    $lists = $lists . '     <input class="form-control" name="txtDescription[]" id="txtDescription[]" value="">';
    $lists = $lists . '  </div>';
    $lists = $lists . '   <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-3 text-center col-in" style="padding-left:2px;">';
    $lists = $lists . '     <input class="form-control number" name="GrossWeight[]" onkeypress="return floatOnly(event);" id="GrossWeight[]" value="">';
    $lists = $lists . ' </div>';
     $lists = $lists .'<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col-1 text-center col-in"><a href="javascript:void(0);" id="remCF" class="btn btn-danger btn-del-mini btn-round"><i class="fa fa-close"></i></a> </div> ';
    $lists = $lists . ' </div>';
}

$temp->setReplace("{docno}", $docno);
$temp->setReplace("{txtMv}", $MV);
$temp->setReplace("{countItem}", $CountItem);
$temp->setReplace("{MVLists}", $ddlMV);
$temp->setReplace("{BALists}", $ddl1);
$temp->setReplace("{BAList2}", $ddl2);
$temp->setReplace("{txtLighterNo}", $LighterNo);
$temp->setReplace("{txtBerthedAt}", $BerthedAt);
$temp->setReplace("{CMDate}", $CommencedDate);
$temp->setReplace("{CPDate}", $CompleteDate);
$temp->setReplace("{lists}", $lists);
