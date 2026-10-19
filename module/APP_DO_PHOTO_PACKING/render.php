<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
include "../../initial.php";
if (!isset($pageHtml) || !in_array($pageHtml, array('content_upload', 'content_list'), true)) {
    http_response_code(404);
    exit('Page not found');
}
$temp = new ReplaceHtml(__DIR__ . "/../../template/APP_DO_PHOTO_PACKING/$pageHtml.html");
session_start();
if (isset($_SESSION["tag_id"])) {
    $tag_id = $_SESSION["tag_id"];
} else {
    $tag_id = '';
}
$temp->setReplace("{tag_id}", htmlspecialchars($tag_id, ENT_QUOTES, "UTF-8"));
echo $temp->getReplace();



?>