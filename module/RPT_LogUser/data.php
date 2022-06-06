
<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
require_once "../initial.php";
if ($load == "GetUserAll") {
    $CallModel = new CallModel();
    $CallModel->WebApp_Models();
    $UserModel = new User();
    $UserModel->setConn($ConnWebApp);
    $UserList = $UserModel->GetLogUser();
    $UserModel = null;
    $CallModel = null;
    echo json_encode($UserList);
}

