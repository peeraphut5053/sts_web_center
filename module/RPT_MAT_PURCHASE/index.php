<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../initial.php";

//$CM = new CallModel();
//$CM->SyteLine_Models();
//$GetModel = new PurchaseOrder();
//$GetModel->setConn($ConnSL);
//$GetModelValue = $GetModel->GetRows_SP();
//$options_acct = "";
//foreach ($GetModelValue as $ii => $rr) {
//    $options_acct .= "<option value='" . $rr["acct"] . "'>" . $rr["acct"] . " - " . $rr["description"] . "</option>";
//}
$temp = new ReplaceHtml("../../template/RPT_MAT_PURCHASE/index.html");

//$temp->setReplace("{content}", $temp->getTemplate("./template/RPT_MAT_PURCHASE/index.html"));
//$temp->setReplace("{options_acct}", $options_acct);
echo $temp->getReplace();
sqlsrv_close($ConnSL);
