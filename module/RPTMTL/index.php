<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../initial.php";

$CM = new CallModel();
$CM->SyteLine_Models();
$Location = new SlLocation();
$Location->setConn($ConnSL);
$Locations = $Location->GetRowsAll();

$result = "";
foreach ($Locations as $key => $value) {
    $result = $result . "<option value='" . $value["loc"] . "'>" . $value["description"] . "</option>";
}
$CM = null;
$Locations = null;
$Location = null;
$temp = new ReplaceHtml("../../template/RPTMTL/index.html");

//$temp->setReplace("{content}", $temp->getTemplate("./template/RPTMTL/index.html"));
$temp->setReplace("{options_warehouse}", $result);
echo $temp->getReplace();
sqlsrv_close($ConnSL);

// $temp->setReplace("{ConSlVal}", $ConnWebAppSL);
