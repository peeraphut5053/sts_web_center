<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
include "../../initial.php";
$temp = new ReplaceHtml("../../template/APP_DO_PHOTO_PACKING/$pageHtml.html");
session_start();
if (isset($_SESSION["tag_id"])) {
    $tag_id = $_SESSION["tag_id"];
} else {
    $tag_id = '';
}
$temp->setReplace("{tag_id}", $tag_id);
echo $temp->getReplace();



?>