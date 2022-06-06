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
$u_form = $_SESSION["login_user_import_form"];
$temp = new ReplaceHtml("../template/dialog/SOD/z_dialogImportExcel.html");
$temp->setReplace("{form}", $u_form);

echo $temp->getReplace();



sqlsrv_close($ConnWebApp);
