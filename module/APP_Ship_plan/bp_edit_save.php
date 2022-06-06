<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "./initial.php";
//assign value 
$result = "";
$txtHeadCode = "";
$selBA = 0;
$selMV = 0;

$txtStartDate = "";
//$txtCpDate = "";
$arrIdRun = array();
$arrItemTag = array();
$arrItemPos = array();
$arrCoNum = array();

$CountArr = count($arrItemPos);
/// Header Declare Variable ///
isset($_POST["selMV"]) ? $selMV = $_POST["selMV"] : $selMV = 0;
isset($_POST["selBA"]) ? $selBA = $_POST["selBA"] : $selBA = 0;
isset($_POST["txtHeadCode"]) ? $txtHeadCode = $_POST["txtHeadCode"] : $txtHeadCode = "";
isset($_POST["txtStartDate"]) ? $txtStartDate = $_POST["txtStartDate"] : $txtStartDate = "";


/// Lists Declare Variable ///
isset($_POST["txtItemTag"]) ? $arrItemTag = $_POST["txtItemTag"] : $arrItemTag = array();
isset($_POST["hdItemPos"]) ? $arrItemPos = $_POST["hdItemPos"] : $arrItemPos = array();
isset($_POST["txtCoNum"]) ? $arrCoNum = $_POST["txtCoNum"] : $arrCoNum = array();
/// Head Object value///
$BoatPlanHead = new BoatPlanHead();
$BoatPlanHead->setConn($conn);
$BoatPlanHead->_HeadCode = $txtHeadCode;
$BoatPlanHead->_Boat_Id = $selMV;
$BoatPlanHead->_Berthed_Id = $selBA;
$BoatPlanHead->_StartDate = $txtStartDate;
$BoatPlanHead->update();
///Lists object value////
$BoatPlanList = new BoatPlanList();
$BoatPlanList->setConn($conn);
$BoatPlanList->_HeadCode = $txtHeadCode;
$BoatPlanList->deleteWithHeader();

$tmpBundles = 0;
$tmpGrossWeight = 0;
$tmpShipTo=0 ;
foreach ($arrItemPos as $key => $val) {  
    $BoatPlanList->_HeadCode = $txtHeadCode;
    $BoatPlanList->_ItemTag = $arrItemTag[$key];
    $BoatPlanList->_itemPosition = $arrItemPos[$key];
    $BoatPlanList->_CoNum = $arrCoNum[$key];
    $BoatPlanList->insert();
}
echo $txtHeadCode;

sqlsrv_close($conn);



