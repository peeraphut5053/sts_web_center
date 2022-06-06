<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "./initial.php";

$result = "";
$txtHeadCode = "";
$txtMV = "";
$txtLighterNo = "";
$txtBerthedAt = "";
$txtStartDate = "";
$txtEndDate = "";

$arrItemTag = array();
$arrPosition = array();
$arrItemQty = array();
$arrItemDesc = array();
$arrCoNum= array();
$CountArr = count($arrItemTag);
if (isset($_POST["txtHeadCode"])) {
    $txtHeadCode = $_POST["txtHeadCode"];
}
if (isset($_POST["selMV"])) {
    $txtMV = $_POST["selMV"];
}
//if (isset($_POST["txtLighterNo"])) {
//    $txtLighterNo = $_POST["txtLighterNo"];
//}
if (isset($_POST["selBA"])) {
    $txtBerthedAt = $_POST["selBA"];
}
if (isset($_POST["txtStartDate"])) {
    $txtStartDate= $_POST["txtStartDate"];
}
if (isset($_POST["txtEndDate"])) {
    $txtEndDate = $_POST["txtEndDate"];
}

if (isset($_POST["txtItemTag"])) {
    $arrItemTag = $_POST["txtItemTag"];
}
if (isset($_POST["txtDescription"])) {
    $arrItemDesc= $_POST["txtDescription"];
}
if (isset($_POST["txtQTY"])) {
    $arrItemQty= $_POST["txtQTY"];
}
if (isset($_POST["txtItemPos"])) {
    $arrPosition = $_POST["hdItemPos"];
}
if (isset($_POST["txtCoNum"])) {
    $arrCoNum = $_POST["txtCoNum"];
}
/// Head Object ///
$BoatPlanHead = new BoatPlanHead();
$BoatPlanHead->setConn($conn);
$BoatPlanHead->_HeadCode = $txtHeadCode;
$BoatPlanHead->_Boat_Id = $selMV;
//$BoatPlanHead->_LighterNo = $txtLighterNo;
$BoatPlanHead->_Berthed_Id = $selBA;
$BoatPlanHead->_StartDate = $txtStartDate;
$BoatPlanHead->_EndDate = $txtEndDate;
$BoatPlanHead->update();
///Lists object ////
$BoatPlanList = new BoatPlanList();
$BoatPlanList->setConn($conn);
foreach ($arrItemTag as $key => $val) {    
    $BoatPlanList->_HeadCode = $txtHeadCode;
    $BoatPlanList->_ItemTag = $arrItemTag[$key];
    $BoatPlanList->_itemPosition =$arrPosition[$key];  
    $BoatPlanList->_CoNum =$arrCoNum[$key];  
    $BoatPlanList->_ItemQty = $arrItemQty[$key] ;
    $BoatPlanList->_ItemDescription = $arrItemDesc[$key];
    $BoatPlanList->insert();
}
echo $txtHeadCode;

sqlsrv_close($conn);



