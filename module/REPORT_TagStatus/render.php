<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

include "../../initial.php";

$temp = new ReplaceHtml("../../template/REPORT_TagStatus/$pageHtml.html");

echo $temp->getReplace();
?>