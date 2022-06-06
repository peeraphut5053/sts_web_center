<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../initial.php";

$temp = new ReplaceHtml("../../template/RPT_STOCK_COUNT_ITEM_LOCATION_SCAN/index.html");
$CM = new CallModel();
$CM->SyteLine_Models();
$Location = new SlLocation();
$Location->setConn($ConnSL);
$Locations = $Location->GetRowsAll();

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

