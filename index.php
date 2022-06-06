

<?php

session_start();
$ajax = isset($_GET['ajax']) ? $_GET['ajax'] : '';



if ($ajax == 'changeDatabase') {
    $_SESSION["DATABASE"] = $_GET["DATABASE"];
}

if(!$_SESSION["DATABASE"]){
    $_SESSION["DATABASE"] = 'Live_App';
}


//$DATABASE = $_GET["DATABASE"];


$uri = explode("?", trim($_SERVER['REQUEST_URI']));
//Array ( [0] => /STS_WEB_CENTER_NEW/ )

if (!empty($uri[1])) {

    if ($uri[1]{
    0} == "/") {
        header("Location: ." . $uri[1] . "?" . $uri[2]);
    }

    $uri_p = explode("&", trim($uri[1]));
    if (is_file("./module/" . $uri_p[0] . ".php")) {
        include "./module/" . $uri_p[0] . ".php";
    } else {
        include "./module/error.php";
    }

} else {
    
    if (isset($_SESSION["login_user_id"])) {

        // include "./include/lib.php";
        include "./initial.php";
        $temp = new ReplaceHtml("./template/tmp_main.html");
        if (isset($_SESSION["CurrentPageUrl"])) {
            //$loadPage = $_SESSION["CurrentPageUrl"];
            $loadPage = "./USER_MNG/index.html";
            //$loadPage = "./" . $_SESSION["CurrentPageUrl"] . "/index.html";
        } else {
            $loadPage = "dashboard.html";
        }
        $CurrPage = "";

        $content = file_get_contents('template/' . $loadPage);

        $temp->setReplace("{content}", $content);
        $CurrentPage = "";
        if (isset($_SESSION["CurrentPageUrl"])) {
            $CurrentPage = $_SESSION["CurrentPageUrl"];
        }
        $temp->setReplace("{CurrentPageLiving}", isset($_SESSION["CurrentPageLiving"]) ? $_SESSION["CurrentPageLiving"] : "");
        $temp->setReplace("{CurrentPageUrl}", isset($_SESSION["CurrentPageUrl"])) ? $_SESSION["CurrentPageUrl"] : "";
        $temp->setReplace("{CurrentPage}", $CurrentPage);
        $temp->setReplace("{CurrentPageLeftMenuId}", isset($_SESSION["CurrentPageLeftMenuId"]) ? $_SESSION["CurrentPageLeftMenuId"] : "");
        $temp->setReplace("{CurrentPageToggleMenu}", isset($_SESSION["CurrentPageToggleMenu"]) ? $_SESSION["CurrentPageToggleMenu"] : "");
        $temp->setReplace("{CurrentPageBtnDep}", isset($_SESSION["CurrentPageBtnDep"]) ? $_SESSION["CurrentPageBtnDep"] : "");

        echo "<input id='user_prop' DATABASE='".$_SESSION["DATABASE"]."' type='hidden' login_username='" . $_SESSION["login_username"] . "' login_user_id='" . $_SESSION["login_user_id"] . "' />";
        $ss = "";

        $CM = new CallModel();
        $CM->WebApp_Models();
        $Dep = new Department();
        $Dep->setConn($ConnWebApp);
        $User = new User();
        $User->setConn($ConnWebApp);
        $follow = $User->GetPropertiesUser($_SESSION["login_user_id"]);
        $follow = trim($User->follow_department);
        $dep_id = $User->dep;
        if ($follow == 'true') {
            $Deps = $Dep->GetRowsFollowDepartment(" WHERE dep_id < 999 and dep.dep_id = $dep_id ");
        } else {
            $Deps = $Dep->GetRows(" WHERE dep_id < 999 ");
        }
        $PRJ = new Project();
        $PRJ->setConn($ConnWebApp);
        $AllDeps = "";
        if (!isset($user_type)) {
            $user_type = "";
        }
        foreach ($Deps as $ii => $rr) {
            $PRJ->_dep_id = $rr["dep_id"];
            $PRJS = $PRJ->GetAPP_All();
            //$PRJS = $PRJ->GetAPP();
            //print_r($PRJS);
            $DepId = $rr["dep_id"];
            $DepName = $rr["dep_name"];
            $DepIcon = $rr["dep_icon"];
            $AllDeps .= ' <li class="treeview" width=2000>
          <a href="#">
            <i class="' . $DepIcon . '"></i>
            <span>' . $DepName . '</span>
            <span class="pull-right-container width=2000">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu" >';
            foreach ($PRJS as $prj_index => $prj_rows) {
                $AllDeps .= ' <li  ><a  OnClick="LeftMenuClick(this.id);" id="LeftMainBtn_' . $prj_rows["prj_id"] . '" href="#" link="' . $prj_rows["prj_link"] . '">'
                    . '<i  id="LeftCircle_' . $prj_rows["prj_id"] . '" class="fa fa-circle-o"></i> <span class="ow" >' . $prj_rows["prj_description"] . '</span></a></li>';
            }
            $AllDeps .= '   </ul> </li>';
            $PRJS = null;
        }
        $PRJ = null;
        $Dep = null;
        $Deps = null;

        $temp->setReplace("{AllDeps}", $AllDeps);
        sqlsrv_close($ConnWebApp);
        sqlsrv_close($ConnSL);
        echo $temp->getReplace();
    } else {
        include "./initial.php";
        $temp = new ReplaceHtml("./template/login.html");
        $loadPage = "login.html";
        $CurrPage = "";
        $content = file_get_contents('template/' . $loadPage);
        $temp->setReplace("{AllDeps}", $content);
        sqlsrv_close($ConnWebApp);
        sqlsrv_close($ConnSL);
        echo $temp->getReplace();
    }
}
//echo '<pre>';
//var_dump($_SESSION);
//echo '</pre>';
?>
