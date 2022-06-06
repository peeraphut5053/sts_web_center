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
$txtCmDate = "";
$txtCpDate = "";

$arrLotNo = array();
$arrTo = array();
$arrBundles = array();
$arrDescription = array();
$arrGrossWeight = array();

$CountArr = count($arrLotNo);
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
if (isset($_POST["CMDate"])) {
    $txtCmDate = $_POST["CMDate"];
}
if (isset($_POST["CPDate"])) {
    $txtCpDate = $_POST["CPDate"];
}

if (isset($_POST["txtLotNo"])) {
    $arrLotNo = $_POST["txtLotNo"];
}
if (isset($_POST["selTO"])) {
    $arrTo = $_POST["selTO"];
}
if (isset($_POST["txtBundles"])) {
    $arrBundles = $_POST["txtBundles"];
}
if (isset($_POST["txtDescription"])) {
    $arrDescription = $_POST["txtDescription"];
}
if (isset($_POST["GrossWeight"])) {
    $arrGrossWeight = $_POST["GrossWeight"];
}
/// Head Object ///
$BoatNoteHead = new BoatNoteHead();
$BoatNoteHead->setConn($conn);
$BoatNoteHead->_HeadCode = $txtHeadCode;
$BoatNoteHead->_Boat_Id = $selMV;
//$BoatNoteHead->_LighterNo = $txtLighterNo;
$BoatNoteHead->_Berthed_Id = $selBA;
$BoatNoteHead->_CommencedDate = $txtCmDate;
$BoatNoteHead->_CompleteDate = $txtCpDate;
$BoatNoteHead->update();
///Lists object ////
$BoatNoteList = new BoatNoteList();
$BoatNoteList->setConn($conn);

foreach ($arrLotNo as $key => $val) {
    if ($arrBundles[$key] != "") {
        $tmpBundles = $arrBundles[$key];
    }
    if ($arrGrossWeight[$key] != "") {
        $tmpGrossWeight = $arrGrossWeight[$key];
    }
    $BoatNoteList->_HeadCode = $txtHeadCode;
    $BoatNoteList->_Description = $arrDescription[$key];
    $BoatNoteList->_Bundles =$tmpBundles;
    $BoatNoteList->_GrossWeight = $tmpGrossWeight;
    $BoatNoteList->_LotNo = $arrLotNo[$key];
    $BoatNoteList->_ShipTo =$arrTo[$key];
    $BoatNoteList->insert();
}
echo $txtHeadCode;

sqlsrv_close($conn);



