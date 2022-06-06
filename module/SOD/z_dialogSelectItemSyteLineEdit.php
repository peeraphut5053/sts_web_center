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
$temp = new ReplaceHtml("../template/dialog/SOD/z_dialogSelectItemSyteLineEdit.html");

$U = new User();
$U->setConn($ConnWebApp);
$lId = $U->GetlatestId();
$temp->setReplace("{last_id}", $lId);

echo $temp->getReplace();



sqlsrv_close($ConnWebApp);
