<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../initial.php";

$CM = new CallModel();
$CM->SyteLine_Models();
$GetModel = new Chart();
$GetModel->setConn($ConnSL);
$GetModelValue = $GetModel->GetRows();
$options_whse = "";
foreach ($GetModelValue as $ii => $rr) {
    $options_whse .= "";
    $options_whse .= "<option value='" . $rr["acct"] . "'>" . $rr["acct"] . " - " . $rr["description"] . "</option>";
}
$temp = new ReplaceHtml("../../template/RPTGL/index.html");

//$temp->setReplace("{content}", $temp->getTemplate("./template/RPTGL/index.html"));
$temp->setReplace("{options_whse}", $options_whse);
echo $temp->getReplace();
sqlsrv_close($ConnSL);
