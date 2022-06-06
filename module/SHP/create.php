<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
$CallModel = new CallModel();
$CallModel->SHP_Models();

$temp->setReplace("{crumb}", "");
$temp->setReplace("{pagename}", "Lists");
$temp->setReplace("{content}", $temp->getTemplate("./template/SHP/create.html"));
$BoatPlanHead = new BoatPlanHead();
$BoatPlanHead->setConn($ConnWebApp);
$docno= $BoatPlanHead->GenNewHeadCode();
//Populate Boat list
$Boat = new Boat();
$Boat->setConn($ConnWebApp);
$LN= "" ;
$Selected = '' ;
if(!empty($_GET["selMV"])){
  $Boat->_IdRun = $_GET["selMV"] ;
  $Boat->GetProperty();
  $LN=$Boat->Ship_LighterNo ;
  $Selected =$_GET["selMV"] ;
}
$ddlMV = $Boat->PopulateAll( 0 , $Selected);

//Populate Harbor list
$Harbor = new Harbor();
$Harbor->setConn($ConnWebApp);
$HN= "" ;
$Selected2 = '' ;
if(!empty($_GET["selBA"])){
  $Harbor->_IdRun = $_GET["selBA"] ;
  $Harbor->GetProperty();
  $HN=$Harbor->HB_Name ;
  $Selected2 =$_GET["selBA"] ;
}
//$ddlMV2 = $Harbor->Populate("HB_Status = 2 OR  HB_Status = 0 ",0, $Selected2);
//$ddlMV3 = $Harbor->Populate("HB_Status = 2 OR  HB_Status = 1 ",0, 0);

$Pos = new BoatPosition();
$Pos->setConn($ConnWebApp);
$ddlPos = $Pos->PopulateAll(0, 0);
//sqlsrv_close($ConnWebApp);
$temp->setReplace("{LighterNo}", $LN);
$temp->setReplace("{MVLists}", $ddlMV);
//$temp->setReplace("{BALists}", $ddlMV2);
$temp->setReplace("{PosLists}", $ddlPos);
//$temp->setReplace("{BAListsTo}", $ddlMV3);
$temp->setReplace("{docno}", $docno);
