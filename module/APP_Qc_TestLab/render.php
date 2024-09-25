<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

include "../../initial.php";

$temp = new ReplaceHtml("../../template/APP_Qc_TestLab/$pageHtml.html");

echo $temp->getReplace();
?>