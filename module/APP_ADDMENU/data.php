<?php

// 
foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

require_once "../initial.php";

if ($load == 'ajax') {
    $CallModel = new CallModel();
    $CallModel->WebApp_Models();
    $ProjectModel = new Project();
    $ProjectModel->setConn($ConnWebApp);
    $ProjectModel->_prj_code = $prj_code;
    $ProjectModel->_prj_name = $prj_code;
    $ProjectModel->_prj_description = $prj_description;
    $ProjectModel->_prj_icon_fa = "fa fa-cubes";
    $ProjectModel->_prj_link = "?" . $prj_code . "/index";
    $ProjectModel->_prj_status = 1;
    $ProjectModel->_prj_type = "N";
    $ProjectModel->_prj_color = "";
    if($dep_id=="") {
        $dep_id = 0;
    }
    $ProjectModel->_dep_id = $dep_id;
    $ProjectSave = $ProjectModel->SaveMenu();
    echo json_encode($ProjectSave);
}






