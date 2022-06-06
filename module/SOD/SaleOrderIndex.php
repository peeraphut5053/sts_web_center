<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}

if (empty($_SESSION["login_username"])) {
    header("location: ?login");
}
//============== Render Page Normal ================//
//if (!isset($_POST["action"])) {

$temp->setReplace("{content}", $temp->getTemplate("./template/SOD/SaleOrderIndex.html"));
$fltStartDate = null;
$fltEndDate = null;
$fltStatus = "0";
$fltKeyword = "";

$keyword = "";
!$fltEndDate ? $endDate = date("Y-m-d") : $endDate = $fltEndDate;
!$fltStartDate ? $startDate = date("Y-m-d", strtotime("-30 days", strtotime($endDate))) : $startDate = $fltStartDate;
!$fltStatus ? $status = $fltStatus : $status = $fltStatus;
$fltKeyword ? $keyword = $fltKeyword : $keyword = "";

$login_type = $_SESSION["login_type"];
if ($login_type == "A") {
    $temp->setReplace("{btnCreateNormal}", "hidden-xs hidden-md hidden-lg hidden-sm");
    $temp->setReplace("{btnCreateAdmin}", "");
} else {
    $temp->setReplace("{btnCreateNormal}", "");
    $temp->setReplace("{btnCreateAdmin}", "hidden-xs hidden-md hidden-lg hidden-sm");
}


$temp->setReplace("{fltStatus}", $status);
$temp->setReplace("{fltEndDate}", $endDate);
$temp->setReplace("{fltStartDate}", $startDate);
$temp->setReplace("{fltKeyword}", $keyword);




