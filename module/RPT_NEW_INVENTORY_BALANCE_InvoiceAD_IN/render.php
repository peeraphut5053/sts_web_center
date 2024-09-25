<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

include "../../initial.php";

$temp = new ReplaceHtml("../../template/RPT_NEW_INVENTORY_BALANCE_InvoiceAD_IN/$pageHtml.html");

echo $temp->getReplace();
?>