<?php

//===========initial file requirement=========//
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../initial.php";


if ($form == "Detail") {

    $temp = new ReplaceHtml("../../template/RPTITEM_RESULT/Detail.html");
    $temp->setReplace("{StartDate}",$StartDate);
    $temp->setReplace("{EndDate}", $EndDate);
    $temp->setReplace("{item_group}", $item_group);
    echo $temp->getReplace();
}
