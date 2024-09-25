<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

include "../../initial.php";

$temp = new ReplaceHtml("../../template/RPT_Stock_Order_Planning/$pageHtml.html");

echo $temp->getReplace();
?>