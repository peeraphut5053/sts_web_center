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
$temp = new ReplaceHtml("../template/dialog/z_dialogUserAdd.html");

$U = new User();
$U->setConn($ConnWebApp);
$lId = $U->GetlatestId();
$temp->setReplace("{last_id}", $lId);
//$Dep = new Department();
//$Dep->setConn($ConnWebApp);
//$rDep = $Dep->GetRows("");
//$options_dep = "<option value='0'>..Select..</option>" ;
//foreach($rDep as $iiDep => $rrDep){
//    $options_dep ="<option value='".$rrDep["dep_id"]."'>".$rrDep["dep_name"]."</option>";
//}
//$temp->setReplace("{options_dep}", $options_dep);

echo $temp->getReplace();



sqlsrv_close($ConnWebApp);
