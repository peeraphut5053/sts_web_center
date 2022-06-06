<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../initial.php";

$temp = new ReplaceHtml("../../template/RPTHR/index.html");
$CM = new CallModel();
$CM->SyteLine_Models();
$Location = new SlLocation();
$Location->setConn($ConnSL);
$Locations = $Location->GetLocationAll();

$result = "";
$result = $result . "<option value='ALL_A1'>A1 ทั้งหมด</option>";
$result = $result . "<option value='ALL_A2'>A2 ทั้งหมด</option>";
$result = $result . "<option value='ALL_A3'>A3 ทั้งหมด</option>";
$result = $result . "<option value='ALL_A4'>A4 ทั้งหมด</option>";
$result = $result . "<option value='ALL_A5'>A5 ทั้งหมด</option>";
$result = $result . "<option value='ALL_A6'>A6 ทั้งหมด</option>";
foreach ($Locations as $key => $value) {
    $result = $result . "<option value='" . $value["loc"] . "'>" . $value["loc_description"] . "</option>";
}
$CM = null;
$Locations = null;
$Location = null;

//$temp->setReplace("{content}", $temp->getTemplate("./template/RPTHR/index.html"));
$temp->setReplace("{options_warehouse}", $result);
echo $temp->getReplace();
sqlsrv_close($ConnSL);


//if (strpos("ALLP1", 'ALL') !== false) {
//    $RR="ALL_A1";
//    $ep = explode("_",$RR);
//   echo $ep[1] ;
//} else {
//     echo "2" ;
//}

//$temp->setReplace("{options_thickness}", $result2);
// $temp->setReplace("{ConSlVal}", $ConnWebAppSL);
