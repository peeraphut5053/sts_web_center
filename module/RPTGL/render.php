<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
include "../../initial.php";



$temp = new ReplaceHtml("../../template/RPTGL/$pageHtml.html");

$CM = new CallModel();
$CM->SyteLine_Models();
$GetModel = new Chart();
$GetModel->setConn($ConnSL);
$GetModelValue = $GetModel->GetRows();
$options_acct = "";
foreach ($GetModelValue as $ii => $rr) {
    $options_acct .= "<option value='" . $rr["acct"] . "'>" . $rr["acct"] . " - " . $rr["description"] . "</option>";
}
//$temp->setReplace("{content}", $temp->getTemplate("./template/RPTGL/content_rptgl.html"));
$temp->setReplace("{options_acct}", $options_acct);
echo $temp->getReplace();








?>