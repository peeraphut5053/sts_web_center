<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
include "../../initial.php";

$temp = new ReplaceHtml("../../template/RPT_STOCK_COUNT_ITEM_LOCATION_SCAN/$pageHtml.html ");
if (isset($id_hdr)) {
    $temp->setReplace("{id_hdr}", "$id_hdr");
} else {
    $temp->setReplace("{id_hdr}", "");
}
if (isset($location)) {
    $temp->setReplace("{location}", "$location");
} else {
    $temp->setReplace("{location}", "");
}

echo $temp->getReplace();
?>