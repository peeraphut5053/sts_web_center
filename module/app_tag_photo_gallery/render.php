<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
include "../../initial.php";

$temp = new ReplaceHtml("../../template/app_tag_photo_gallery/$pageHtml.html");

echo $temp->getReplace();



?>