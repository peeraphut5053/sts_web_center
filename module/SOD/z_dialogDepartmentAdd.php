<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
session_start();
if (empty($_SESSION["login_username"])) {
    header("location: ?login");
}
//============== Render Page Normal ================//
include "./initial.php";

$u_name = $_SESSION["login_username"];
$temp = new ReplaceHtml("../template/dialog/SOD/z_dialogDepartmentAdd.html");
$Sec = new Section();
$Sec->setConn($ConnWebApp);
$Secs = $Sec->GetItemToDropdown("");
$Section_Options = "<option value='0'>..Select..</option>";
foreach ($Secs as $iSec => $rSec) {
    $Section_Options = $Section_Options . "<option value='$iSec'>$rSec</option>";
}

$User = new User();
$User->setConn($ConnWebApp);
$Users = $User->GetItemToDropdown(" WHERE sys_level > 2 ");
$User_Options = "<option value='0'>..Select..</option>";
foreach ($Users as $iU => $rU) {
    $User_Options = $User_Options . "<option value='$iU'>$rU</option>";
}
$temp->setReplace("{Section_Options}", $Section_Options);
$temp->setReplace("{User_Options}", $User_Options);
echo $temp->getReplace();



sqlsrv_close($ConnWebApp);
