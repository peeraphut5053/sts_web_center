<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//include "./initial.php";

$tblLines="";
//$temp = new ReplaceHtml("./template/CDT_Main.html");
$temp->setReplace("{content}", $temp->getTemplate("./template/CDT_Main.html"));
$temp->setReplace("{table_list}", $tblLines);
//echo $temp->getReplace();
// $temp->setReplace("{ConSlVal}", $ConnSL);
