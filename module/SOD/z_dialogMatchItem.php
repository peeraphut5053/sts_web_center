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

$NPS = "";
$CATE = "";
$SCHED = "";
$WALL = "" ;
$GRADE = "" ;
$LEN = "" ;
$Item = new ItemSyteLine();
$Item->setConn($ConnWebApp);
$Item->_Uf_Nps = $NPS ;
$Item->_Uf_grade = $GRADE ;
//$Item->_Uf_length =$LEN ;
$Item->_Uf_sched = $SCHED; 
$Item->_item_category="";



$temp = new ReplaceHtml("../template/dialog/SOD/z_dialogMatchItem.html");
$temp->setReplace("{form}", $u_form);
$Item = new ItemSyteLine();
echo $temp->getReplace();



sqlsrv_close($ConnWebApp);
