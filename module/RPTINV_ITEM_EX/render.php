<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
include "../../initial.php";



$temp = new ReplaceHtml("../../template/RPTINV_ITEM_EX/$pageHtml.html");


$CallModel = new CallModel();
$CallModel->SyteLine_Models();


$CustModels = new Invoice();
$CustModels->setConn($ConnSL);
$CustOptions = $CustModels->GetOptionEndCust();
$options = "";
$options.="<option value=''>----------- เลือก -----------</option>";
foreach ($CustOptions as $index => $rows) {
    $options.="<option value='" . $rows["cust_num"] . "'>(" . $rows["cust_num"] . ")" . $rows["name"] . "</option>";
}
$CustModels = null;



$CountryModels = new Invoice();
$CountryModels->setConn($ConnSL);
$CountryOptions = $CountryModels->GetOptionCountry();
$optionsCountry = "";
$optionsCountry.="<option value=''>------ เลือกประเทศ ------</option>";
foreach ($CountryOptions as $index => $rows) {
    $optionsCountry.="<option value='" . $rows["country"] . "'>" . $rows["country"] . "</option>";
}
$CustModels = null;


$temp->setReplace("{CustOptions}", $options);
$temp->setReplace("{CountryOptions}", $optionsCountry);


echo $temp->getReplace();
?>