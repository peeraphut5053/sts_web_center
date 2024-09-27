<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}

include "../../initial.php";

$temp = new ReplaceHtml("../../template/RPT_NEW_INVENTORY_BALANCE_InvoiceAD_IN/$pageHtml.html");

echo $temp->getReplace();
?>