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
$temp = new ReplaceHtml("../template/dialog/SOD/z_dialogPortAdd.html");
//$temp->setReplace("{Customer}", $cust_num);
$temp->setReplace("{Customer}", $cust_name);

//$temp->setReplace("{User_Options}", $User_Options);
echo $temp->getReplace();



sqlsrv_close($ConnWebApp);
