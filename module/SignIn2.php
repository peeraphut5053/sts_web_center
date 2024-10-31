
<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}


//============== Render Page Normal ================//
include "./initial.php";
require_once __DIR__ . '/../vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
$CM = new CallModel();
$CM->WebApp_Models();
//============== Render Ajax =======================//


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($action == "Login") {
    $User = new User();
    $User->setConn($ConnWebApp);
    $User->_username = $username;
    $User->_password = $password;
    $ResultLogin = $User->Login();

    $payload = [
        'username' => $username,
        'iat' => time(),
        'exp' => time() + (60 * 60 * 24), // 1 day
        'token' => bin2hex(random_bytes(16)) // token id
    ];

    $logInfo = array();
    if ($ResultLogin != 1) {
        $logInfo["state"] = 0;
        $logInfo["query"] = $User->query;
        $logInfo["msg"] = "Error ! Wrong Usernamr or password  ";
        $logInfo["uid"] = '';
        $logInfo["uname"] = '';
        echo http_response_code(401);
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
    //สร้าง object ข้อมูลสำหรับทำ jwt
    $payload = array(
        "user" => $User->username,
        "exp" => time() + (60 * 60 * 24),
        "date_time" => date("Y-m-d H:i:s")//กำหนดวันเวลาที่สร้าง
    );
    //สร้าง JWT สำหรับ object ข้อมูล
    $jwt = JWT::encode($payload, $key, 'HS256');
    //เพื่อความปลาดภัยยิ่งขึ้นเมื่อได้ JWT แล้วควรเข้ารหัสอีกชั้นหนึ่ง
  
    echo json_encode(array(
        "username" => $logInfo["uname"],
        "token" => $jwt
    ));
}

if ($action == "CheckAuth") {
    try {
        // ถอดรหัส JWT และตรวจสอบความถูกต้อง
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        echo json_encode($decoded);
    } catch (Exception $e) {
        // หากโทเคนไม่ถูกต้องหรือหมดอายุ
        echo json_encode(false);
    }
}