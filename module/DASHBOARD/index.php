<?php

//============= initial module =====
while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key
            } = trim($data);
}
//================== New Ver DASHBOARD ========
include "../initial.php";


$temp = new ReplaceHtml("../../template/DASHBOARD/index.html");
$CallModel = new CallModel();
$CallModel->SyteLine_Models();

//======= get 1 value from model ============
//$data = new JOBORDER();
//$data->setConn($ConnSL);
//$data = $data->GetJobOrrderProcessingQty();
//$temp->setReplace("{job_order_procressing}", $data[0]["countqty"]);


$CallModel->WebApp_Models();
$UserModel = new User();
$UserModel->setConn($ConnWebApp);


//$_SESSION["login_user_id"] = 1;
$login_user_id = $_SESSION["login_user_id"];
$PrjList = $UserModel->GetQuickMenu($login_user_id);
//echo "<pre>";
//echo print_r($PrjList);
//echo "</pre>";



$quick_menu = "<div class='row' >";
foreach ($PrjList as $ii => $rr) {
    if ($rr["quick_id"] % 4 == 1) {
        $quick_menu_color = "btn-info";
    } else if ($rr["quick_id"] % 4 == 2) {
        $quick_menu_color = "btn-success";
    } else if ($rr["quick_id"] % 4 == 3) {
        $quick_menu_color = "btn-warning";
    } else if ($rr["quick_id"] % 4 == 0) {
        $quick_menu_color = "btn-danger";
    }
    $quick_menu .="<div class = 'col-lg-3 col-xs-6 btn-group' style='padding: 1px'>";
    $quick_menu .="<button id = 'LeftMainBtn_" . $rr["prj_id"] . "' class = 'btn " . $quick_menu_color . " quick-menu quicklink-" . $rr["quick_id"] . "' onclick = 'QuickMenuClick(this.id);' href = '#' link = '" . $rr["prj_link"] . "' >";
    $quick_menu .="<h4><span id = 'quicklink_prjname-" . $rr["quick_id"] . "'>" . $rr["prj_description"] . "</span></h4> </button>";
    $quick_menu .="<button id = 'quickedit-" . $rr["quick_id"] . "' onclick = 'SetQuickMenu(this.id);' class = 'btn " . $quick_menu_color . " quick-menu-edit'><span class = 'fa fa-gear'></span></button></div>";

    if($rr["quick_id"] % 4 == 0) {
        $quick_menu .= "</div><div class='row'>";
    }
}
$quick_menu .= "</div>";

$temp->setReplace("{quick_menu}", $quick_menu);


//================== New Ver DASHBOARD ========
echo $temp->getReplace();
sqlsrv_close($ConnSL);
//================== New Ver DASHBOARD ========
