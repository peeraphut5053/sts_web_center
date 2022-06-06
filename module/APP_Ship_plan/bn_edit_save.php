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

$txtCmDate = "";
$txtCpDate = "";
$arrIdRun = array();
$arrLotNo = array();
$arrShipTo = array();
$arrBundles = array();
$arrDescription = array();
$arrGrossWeight = array();

$CountArr = count($arrLotNo);
/// Header Declare Variable ///
isset($_POST["selMV"]) ? $selMV = $_POST["selMV"] : $selMV = 0;
isset($_POST["selBA"]) ? $selBA = $_POST["selBA"] : $selBA = 0;
//isset($_POST["txtBerthedAt"]) ? $txtBerthedAt = $_POST["txtBerthedAt"] : $txtBerthedAt = "";
isset($_POST["txtHeadCode"]) ? $txtHeadCode = $_POST["txtHeadCode"] : $txtHeadCode = "";
isset($_POST["CMDate"]) ? $txtCmDate = $_POST["CMDate"] : $txtCmDate = "";
isset($_POST["CPDate"]) ? $txtCpDate = $_POST["CPDate"] : $txtCpDate = "";


/// Lists Declare Variable ///
isset($_POST["txtDescription"]) ? $arrDescription = $_POST["txtDescription"] : $arrDescription = array();
isset($_POST["GrossWeight"]) ? $arrGrossWeight = $_POST["GrossWeight"] : $arrGrossWeight = array();
isset($_POST["txtBundles"]) ? $arrBundles = $_POST["txtBundles"] : $arrBundles = array();
isset($_POST["txtLotNo"]) ? $arrLotNo = $_POST["txtLotNo"] : $arrLotNo = array();
isset($_POST["hdfId"]) ? $arrIdRun = $_POST["hdfId"] : $arrIdRun = array();
isset($_POST["selTO"]) ? $arrShipTo = $_POST["selTO"] : $arrShipTo =array();
/// Head Object value///
$BoatNoteHead = new BoatNoteHead();
$BoatNoteHead->setConn($conn);
$BoatNoteHead->_HeadCode = $txtHeadCode;
$BoatNoteHead->_Boat_Id = $selMV;
//$BoatNoteHead->_LighterNo = $txtLighterNo;
$BoatNoteHead->_Berthed_Id = $selBA;
$BoatNoteHead->_CommencedDate = $txtCmDate;
$BoatNoteHead->_CompleteDate = $txtCpDate;
$BoatNoteHead->update();
///Lists object value////
$BoatNoteList = new BoatNoteList();
$BoatNoteList->setConn($conn);
$BoatNoteList->_HeadCode = $txtHeadCode;
$BoatNoteList->deleteWithHeader();

$tmpBundles = 0;
$tmpGrossWeight = 0;
$tmpShipTo=0 ;
foreach ($arrLotNo as $key => $val) {
    if ($arrBundles[$key] != "") {
        $tmpBundles = $arrBundles[$key];
    }
    if ($arrGrossWeight[$key] != "") {
        $tmpGrossWeight = $arrGrossWeight[$key];
    }
     if ($arrShipTo[$key] != "") {
        $tmpShipTo = $arrShipTo[$key];
    }
    $BoatNoteList->_Bundles = $tmpBundles;
    $BoatNoteList->_GrossWeight = $tmpGrossWeight;
    $BoatNoteList->_Description = $arrDescription[$key];
    $BoatNoteList->_LotNo = $arrLotNo[$key];
    $BoatNoteList->_ShipTo =$tmpShipTo;
    $BoatNoteList->insert();
}
echo $txtHeadCode;

sqlsrv_close($conn);



