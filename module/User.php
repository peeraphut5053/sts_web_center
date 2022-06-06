
<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");


while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}


//============== Render Page Normal ================//
include "./initial.php";
$CM = new CallModel();
$CM->WebApp_Models();
//============== Render Ajax =======================//


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_POST["action"] == "GetRows") {
    $User = new User();
    $User->setConn($ConnWebApp);
    $UserList = $User->GetRows("");
    $rows = array();
    echo json_encode($UserList);
}
if ($_POST["action"] == "GetAPP") {

    $User = new User();
    $User->setConn($ConnWebApp);
    $follow = $User->GetPropertiesUser($_SESSION["login_user_id"]);
    $follow = trim($User->follow_department);
    $Proj = new Project();
    $Proj->setConn($ConnWebApp);
    $Proj->_dep_id = $dep_id;
    $Proj->_prj_type = $prj_type;
    if ($follow == 'true') {
        $Projs = $Proj->GetAPPFollowDepartment();
    } else {
        $Projs = $Proj->GetAPP();
    }
    $Lines = "";
    if (count($Projs) == 0) {
        $Lines = "<br><br><h4><i>...No App Available ...</i></h4>";
    } else {
        foreach ($Projs as $ii => $rr) {
            $Lines = $Lines . " <div onclick='LogUserMenu(" . trim($rr["prj_id"]) . ")' class='col-md-2 col-lg-2 col-xs-3 col-sm-3 '>
                        <div class='row text-center rowcollist'>
                            <a href='" . $rr["prj_link"] . "'>
                                <div class='collist text-center'>
                                    <i class='" . $rr["prj_icon_fa"] . "'></i>
                                </div>
                                <h4>" . $rr["prj_name"] . "</h4>
                                <h5>" . $rr["prj_description"] . "</h5>
                            </a>
                        </div>
                    </div>";
        }
    }
    echo $Lines;
}
if ($_POST["action"] == "GetPropertiesLogin" || $_GET["action"]) {
    $User = new User();
    $User->setConn($ConnWebApp);
    $User->_username = $_POST["username"];
    $User->_password = $_POST["password"];
    $ResultLogin = $User->Login();

    $logInfo = array();
    if ($ResultLogin != 1) {
        $logInfo["state"] = 0;
        $logInfo["query"] = $User->query;
        $logInfo["msg"] = "Error ! Wrong Usernamr or password  ";
        $logInfo["uid"] = '';
        $logInfo["uname"] = '';
    } else {
        $logInfo["state"] = 1;
        $logInfo["msg"] = "Login Successful ";
        $logInfo["query"] = $User->query;
        $logInfo["uid"] = $User->user_id;
        $logInfo["uname"] = $User->username;
        $logInfo["fullname"] = $User->fullname;
        $logInfo["addr1"] = $User->addr1;
        $logInfo["password"] = $User->password;
        $_SESSION["login_type"] = $User->type;
        $_SESSION["login_username"] = $User->username;
        $_SESSION["login_cust_num"] = $User->cust_num;
        $_SESSION["login_user_fullname"] = $User->fullname;
        $_SESSION["login_user_id"] = '123456';
        $_SESSION["login_link_cust_num"] = $User->cust_num_sl;
        $_SESSION["login_ms_id"] = $User->measure_id;
        $_SESSION["login_ms_name"] = $User->measure_name;
        $_SESSION["login_ms_desc"] = $User->measure_desc;
        $_SESSION["login_user_duty"] = $User->flag_duty;
        $_SESSION["login_user_import_form"] = $User->import_form;
        $_SESSION["dep"] = $User->dep;
        $_SESSION["follow_department"] = $User->follow_department;
        $_SESSION["CurrentPageUrl"] = "DASHBOARD";
    }
    echo json_encode($logInfo);
}


if ($_POST["action"] == "LogOut") {
    $User = new User();
    $User->setConn($ConnWebApp);
    $User->id = $_SESSION["login_user_id"];
    $User->LogUserLogout();
    session_destroy();
    echo " Log out successful";
}
if ($_POST["action"] == "LogUserMenu") {
    $U = new User();
    $U->setConn($ConnWebApp);
    $U->_menu = $menu;
    $U->id = $_SESSION["login_user_id"];
    $U->LogUserMenu();

    echo $U->_menu;
}
if ($_POST["action"] == "CheckUserNameDup") {
    $U = new User();
    $U->setConn($ConnWebApp);
    $U->GetProperties(" WHERE username = '" . $_POST["username"] . "' ");
    $Result = "";
    $Result = $U->username;
    $Result == "" ? $R = 0 : $R = 1;
    echo $R;
}
if ($_POST["action"] == "CheckUserNameDupEdit") {

    if ($_POST["old_username"] == $_POST["username"]) {
        $R = 0;
    } else {
        $U = new User();
        $U->setConn($ConnWebApp);
        $U->GetProperties(" WHERE username = '" . $_POST["username"] . "' ");
        $Result = "";
        $Result = $U->username;
        $Result == "" ? $R = 0 : $R = 1;
    }

    echo $R;
}
if ($_POST["action"] == "SaveUser") {
    $U = new User();
    $U->setConn($ConnWebApp);
    $U->_id = $_POST["txtUserId"];
    $U->_username = $_POST["txtUserName"];
    $U->_password = $_POST["txtPassword"];
    $U->_fullname = $_POST["txtFullName"];
    $U->_type = $_POST["type"];
    $U->_sys_level = $_POST["sys_level"];
    $U->_state = $_POST["state"];
    $U->_dep = $_POST["selDep"];
    $U->_addr1 = $_POST["txtAddr1"];
    $U->_addr2 = $_POST["txtAddr2"];
    $U->_tel = $_POST["txtTel"];
    $U->_mobile = $_POST["txtMobile"];
    $U->_email = $_POST["txtEmail"];
    $U->_remark = $_POST["txtRemark"];
    $U->_chk_follow = $_POST["chk_follow"];

    $_POST["saveStat"] == "I" ? $R = $U->insert() : $R = $U->update();
    echo json_encode($R);
}
if ($_POST["action"] == "UpdateUserAuthorize") {
    $result = "";
    $User = new User();
    $User->setConn($ConnWebApp);
    $User->_id = $_POST["user_id"];
    $ArrAuth = $_POST["ArrAuth"];
    $Loop = count($ArrAuth) - 1;
    foreach ($ArrAuth as $index => $val) {
        if ($index > 0) {
            $User->_action = $val;
            $User->_prj_id = $index;
            $result = $result . "Prj_id-" . $index . " result-" . $User->UpdateAuthorize() . "";
        }
    }
    echo $result;
}
if ($_POST["action"] == "GetUserAuthRows") {
    $loginType = "";
    $U = new User();
    $S = new STS_Sys_Config();
    $U->setConn($ConnWebApp);
    $S->setConn($ConnWebApp);
    $S->GetProperties();
    $isAdminLevel = $S->IsAdmin_Level_OverOrEqual;
    $uid = $_POST["user_id"];
    $U->_id = $uid;
    $U->GetProperties(" WHERE u.id = $uid ");
    $UserLevel = $U->sys_level;
    if ($U->ValidateAuthorizeById() == 0) {
        $UserLevel >= $isAdminLevel ? $U->RebuildAuthorizeById(1) : $U->RebuildAuthorizeById(0);
    }
    $R = array();
    $R = $U->GetAuthorizeList(" WHERE u.id = $uid");
    echo json_encode($R);
}

