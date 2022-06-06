<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//include "./initial.php";

$tblLines="";
//$temp = new ReplaceHtml("./template/CDT_Main.html");
$temp->setReplace("{content}", $temp->getTemplate("./template/AxTag/index.html"));
$temp->setReplace("{table_list}", $tblLines);
//echo $temp->getReplace();
// $temp->setReplace("{ConSlVal}", $ConnSL);
