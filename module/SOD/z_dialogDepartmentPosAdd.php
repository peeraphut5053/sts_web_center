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
$temp = new ReplaceHtml("../template/dialog/SOD/z_dialogDepartmentPosAdd.html");
$Department = new Department();
$Department->setConn($ConnWebApp) ; 
$Department->GetProperties(" WHERE dep_id = $dep_id ");

$temp->setReplace("{txtDepName}", $Department->dep_name);
$temp->setReplace("{txtSec}", $Department->sec_name );
$temp->setReplace("{txtDepId}",  $dep_id );
echo $temp->getReplace();



sqlsrv_close($ConnWebApp);
