<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../initial.php";

$temp = new ReplaceHtml("../../template/APP_QTY_MOVE/index.html");

//echo "current db : " ;
    //print_r($strConnSec);
    //echo $host_sec;
 
$CM = new CallModel();
$CM->SyteLine_Models();
 
$MiscData = new Misc();
$MiscData->setConn($ConnSL);
$Whse = $MiscData->GetWhse("");

$options_whse = "";
foreach ($Whse as $ii => $rr) {
    $options_whse .= "";
    $options_whse .= "<option value='" . $rr["whse"] . "'>" . $rr["whse"] . "</option>";
}

$Um = $MiscData->GetUm("");
$options_um = "";
$selected_um = "";
foreach ($Um as $ii => $rr) {
    $options_um .= "<option value=''></option>";
    //$rr["u_m"] =="PCS" ? $selected_um="selected" : $selected_um="";
    $options_um .= "<option $selected_um  value='" . $rr["u_m"] . "'>" . $rr["u_m"] . "</option>";
}

$Reason = $MiscData->GetReason("  WHERE reason_class = 'MISC ISSUE' ");
$options_reason = "";
 
foreach ($Reason as $ii => $rr) {
    $options_reason .= "<option value=''></option>";
    //$rr["u_m"] =="PCS" ? $selected_um="selected" : $selected_um="";
    $options_reason .= "<option   value='" . $rr["reason_code"] . "'>" . $rr["reason_code"]." : ". $rr["reason_description"] . "</option>";
}

$Chart = $MiscData->GetChart("");
$options_chart = "";
 
foreach ($Chart as $ii => $rr) {
    $options_chart .= "<option value=''></option>";
    $options_chart .= "<option   value='" . $rr["acct"] . "'>" . $rr["acct"]." : ". $rr["description"] . "</option>";
}

$temp->setReplace("{options_whse}", $options_whse);
$temp->setReplace("{options_um}", $options_um);
$temp->setReplace("{options_reason}", $options_reason);
$temp->setReplace("{options_chart}", $options_chart);
echo $temp->getReplace();
 



sqlsrv_close($ConnSL);


 