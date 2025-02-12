
<?php

header("Access-Control-Allow-Origin: *");
while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
require_once "../initial.php";

if ($load == "form") {
//    $temp = new ReplaceHtml("../../template/SHP/sl_search_co.html");
//    echo $temp->getReplace();
} else if ($load == "CustomerSelect") {
    $temp->setReplace("{content}", $temp->getTemplate("./template/CER_DO/index.html"));
} else if ($load == "GetUserAll") {
    $CallModel = new CallModel();
    $CallModel->WebApp_Models();
    $UserModel = new User();
    $UserModel->setConn($ConnWebApp);
    $UserList = $UserModel->GetUserAll();
    $UserModel = null;
    $CallModel = null;
    echo json_encode($UserList);
} else if ($load == "GetUserAuthorize") {
    $CallModel = new CallModel();
    $CallModel->WebApp_Models();
    $UserModel = new User();
    $UserModel->setConn($ConnWebApp);
    $UserModel->_id = $id;
    $PrjList = $UserModel->GetProjDectListById2();
    $UserModel = null;
    $CallModel = null;
    echo json_encode($PrjList);
} else if ($load == "ToggleAction") {
    $CallModel = new CallModel();
    $CallModel->WebApp_Models();
    $UserModel = new User();
    $UserModel->setConn($ConnWebApp);
    $UserModel->_id = $UserId;
    $UserModel->_prj_id = $PrjId;
    $UserModel->_action = $CurrAction;
    $Result = $UserModel->ToggleAction();
    $UserModel = null;
    $CallModel = null;
    echo $Result;
} else if ($load == "GetDepartmentAll") {
    $CallModel = new CallModel();
    $CallModel->WebApp_Models();
    $DepModel = new Department();
    $DepModel->setConn($ConnWebApp);
    $Deps = $DepModel->DropdownSelectAllDepartment("");
    $DepModel = null;
    $CallModel = null;
    echo json_encode($Deps);
} else if ($load == "GetDepartmentPosition") {
    $CallModel = new CallModel();
    $CallModel->WebApp_Models();
    $DepModel = new DepartmentPosition();
    $DepModel->setConn($ConnWebApp);
    $Deps = $DepModel->GetRows(" WHERE deppos.dep_id =  $dep_id ");
    $DepModel = null;
    $CallModel = null;
    echo json_encode($Deps);
} else if ($load == "GetUserProp") {
    $CallModel = new CallModel();
    $CallModel->WebApp_Models();
    $UserModel = new User();
    $UserModel->setConn($ConnWebApp);
    $UserData = $UserModel->GetUserData($id);
    echo json_encode($UserData);
} else if ($load == "GetAllProject") {
    $CallModel = new CallModel();
    $CallModel->WebApp_Models();
    $UserModel = new User();
    $UserModel->setConn($ConnWebApp);
    $UserList = $UserModel->GetAllProject();
    $UserModel = null;
    $CallModel = null;
    echo json_encode($UserList);
} else if ($load == "DeleteUser") {
    $CallModel = new CallModel();
    $CallModel->WebApp_Models();
    $UserModel = new User();
    $UserModel->setConn($ConnWebApp);
    $UserDelete = $UserModel->DeleteUserById($user_id);
    echo json_encode($UserDelete);
}


