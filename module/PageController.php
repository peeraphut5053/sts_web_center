<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "initial.php";
$_SESSION["CurrentPageLiving"] = $CurrentPageLiving;
$_SESSION["CurrentPageUrl"] = $CurrentPageLiving;
//$_SESSION["CurrentPageUrl"] = "./".$CurrentPageLiving."/index.html";

$_SESSION["CurrentPageLeftMenuId"] = $CurrentPageLeftMenuId;
//$_SESSION["CurrentPageToggleMenu"] = $CurrentPageToggleMenu;
//$_SESSION["CurrentPageBtnDep"] = $CurrentPageBtnDep;

$result = "CurrentPageLiving:" . $_SESSION["CurrentPageLiving"] . ""
        . ",CurrentPageUrl:" . $_SESSION["CurrentPageUrl"] . ""
        . ",CurrentPageLeftMenuId:" . $_SESSION["CurrentPageLeftMenuId"] . "";
//        . ",CurrentPageToggleMenu:" . $_SESSION["CurrentPageToggleMenu"] . ""
//        . ",CurrentPageBtnDep:" . $_SESSION["CurrentPageBtnDep"] . "";
echo $result;

