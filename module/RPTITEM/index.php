<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}

include "../initial.php";


//
$CM = new CallModel();
$CM->SyteLine_Models();
$Item = new ItemSyteLine();
$Item->setConn($ConnSL);
$yr = $Item->GetYearData();
$GetItemNpsForDropdown = $Item->GetItemNpsForDropdown();
$yrl = "";
$thisYear = date('Y');
$c = "";
$y = "";
foreach ($yr as $ii => $rr) {
    $y = $rr["yr"];
    $yrl .= "<input type='radio'  name='radYear' id='radYear' value='$y' >&nbsp;$y&nbsp;";
}

$NpsForDropdown = "";
foreach ($GetItemNpsForDropdown as $nps_i => $nps_r) {
    $NpsForDropdown .="<option value='" . $nps_r["size"] . "' >" . $nps_r["size"] . "</option>";
}
$result = "";

$temp = new ReplaceHtml("../../template/RPTITEM/index.html");

//$temp->setReplace("{content}", $temp->getTemplate("./template/RPTITEM/index.html"));
$temp->setReplace("{year_list}", $yrl);
$temp->setReplace("{size_list}", $NpsForDropdown);

echo $temp->getReplace();


sqlsrv_close($ConnSL);
