<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
include "../../initial.php";



$temp = new ReplaceHtml("../../template/APP_STORE_WITHDRAW/$pageHtml.html");

$CM = new CallModel();
$CM->SyteLine_Models();
$GetModel = new Chart();
$GetModel->setConn($ConnSL);
echo $temp->getReplace();