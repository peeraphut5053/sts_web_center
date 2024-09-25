<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

include "../initial.php";

$CM = new CallModel();
$CM->SyteLine_Models();
$GetModel = new Customer();
$GetModel->setConn($ConnSL);
$GetModelValue = $GetModel->GetRowsAddr();
$options_cust = "";
foreach ($GetModelValue as $ii => $rr) {
    $options_cust .= "<option value='" . $rr["cust_num"] . "'>" . $rr["cust_name"] . "</option>";
}
$temp = new ReplaceHtml("../../template/RPTCOLLECTION_DAY/index.html");

//$temp->setReplace("{content}", $temp->getTemplate("./template/RPTCOLLECTION_DAY/index.html"));
$temp->setReplace("{options_cust}", $options_cust);
echo $temp->getReplace();
sqlsrv_close($ConnSL);
