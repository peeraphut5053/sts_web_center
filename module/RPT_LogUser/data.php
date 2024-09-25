
<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
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

