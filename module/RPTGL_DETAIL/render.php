<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
include "../../initial.php";


$temp = new ReplaceHtml("../../template/RPTGL_DETAIL/$pageHtml.html");

$CM = new CallModel();
$CM->SyteLine_Models();
$GetModel = new Chart();
$GetModel->setConn($ConnSL);
$GetModelValue = $GetModel->GetRows();
$options_acct = "";
foreach ($GetModelValue as $ii => $rr) {
    $options_acct .= "<option selected disabled value=''></option>"
                    ."<option value='" . $rr["acct"] . "'>" . $rr["acct"] . " - " . $rr["description"] . "</option>";
}
//$temp->setReplace("{content}", $temp->getTemplate("./template/RPTGL/content_rptgl.html"));
$temp->setReplace("{options_acct}", $options_acct);

$GetModelValue = $GetModel->GetUnit1();
$options_unit1 = "";
foreach ($GetModelValue as $ii => $rr) {
    $options_unit1 .= "<option selected disabled value=''></option>"
                    . "<option value='" . $rr["unit1"] . "'>" . $rr["unit1"] . " - " . $rr["description"] . "</option>";
}
$temp->setReplace("{options_unit1}", $options_unit1);
echo $temp->getReplace();








?>