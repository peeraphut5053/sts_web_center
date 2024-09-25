<?php

//===========initial file requirement=========//
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

require_once '../../initial.php';

if ($form == "Authorize") {
    $tabledata = "";
    $CallModel = new CallModel();
    $CallModel->WebApp_Models();
    $UserModel = new User();
    $UserModel->setConn($ConnWebApp);
    $PrjList = $UserModel->GetProjectList($id);
    $UserModel = null;
    $CallModel = null;
    $temp = new ReplaceHtml("../../template/USER_MNG/Authorize.html");
    echo json_encode($PrjList);
} else if ($form == "Update" || $form == "UserDetail") {
    $temp = new ReplaceHtml("../../template/USER_MNG/_From.html");
    $CallModel = new CallModel();
    $CallModel->WebApp_Models();
    $UserModel = new User();
    $UserModel->setConn($ConnWebApp);
    $UserModel->GetPropertiesUser($id);
    $temp->setReplace("{method}", $method);
    $temp->setReplace("{id}", $id);
    $temp->setReplace("{username}", $UserModel->username);
    $temp->setReplace("{password}", $UserModel->password);
    $temp->setReplace("{password2}", $UserModel->password);
    $temp->setReplace("{full_name}", $UserModel->fullname);
    $temp->setReplace("{addr1}", $UserModel->addr1);
    $temp->setReplace("{addr2}", $UserModel->addr2);
    $temp->setReplace("{tel}", $UserModel->tel);
    $temp->setReplace("{mobile}", $UserModel->mobile);
    $temp->setReplace("{email}", $UserModel->email);
    $temp->setReplace("{remark}", $UserModel->remark);
    $temp->setReplace("{dep}", $UserModel->dep);
    $check_state = trim($UserModel->follow_department);
    if ($check_state == 'true') {
        $temp->setReplace("{check_state}", "checked");
    } else {
        $temp->setReplace("{check_state}", "");
    }
    $UserModel = null;
    $CallModel = null;
    echo $temp->getReplace();
} 
