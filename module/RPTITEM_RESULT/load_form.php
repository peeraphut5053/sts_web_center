<?php

//===========initial file requirement=========//
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

include "../initial.php";


if ($form == "Detail") {

    $temp = new ReplaceHtml("../../template/RPTITEM_RESULT/Detail.html");
    $temp->setReplace("{StartDate}",$StartDate);
    $temp->setReplace("{EndDate}", $EndDate);
    $temp->setReplace("{item_group}", $item_group);
    echo $temp->getReplace();
}
