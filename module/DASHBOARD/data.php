<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}
require_once "../initial.php";
if ($load == "form") {
    
} else if ($load == "SetQuickMenuAction") {
    $CallModel = new CallModel();
    $CallModel->WebApp_Models();
    $UserModel = new User();
    $UserModel->setConn($ConnWebApp);
    $UserModel->_id = $UserId;
    $UserModel->_prj_id = $PrjId;
    $UserModel->_action = $CurrAction;
    $UserModel->_quick_id = $quick_id;
    $Result = $UserModel->SetQuickMenuAction();
    $UserModel = null;
    $CallModel = null;
    echo $Result;
} else if ($load == "ConnectionInformation") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $DVR = new DeliveryOrder();
    $DVR->setConn($ConnSL);
    $DVRS = $DVR->ConnectionInformation();
    echo json_encode($DVRS);
} else if ($load == "ConnectionInformation2") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $DVR = new DeliveryOrder();
    $DVR->setConn($ConnSL);
    $DVRS = $DVR->ConnectionInformation2();
    echo json_encode($DVRS);



    function notify_message($message, $token) {
        $queryData = array('message' => $message);
        $queryData = http_build_query($queryData, '', '&');
        $headerOptions = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n"
                . "Authorization: Bearer " . $token . "\r\n"
                . "Content-Length: " . strlen($queryData) . "\r\n",
                'content' => $queryData
            ),
        );
        $context = stream_context_create($headerOptions);
        $result = file_get_contents(LINE_API, FALSE, $context);
        $res = json_decode($result);
        return $res;
    }
    
    define('LINE_API', "https://notify-api.line.me/api/notify");
    $token = "HEaYUZmv6UE1k50BonymacDFF47R1Ud0saI35rxWPyI"; //ใส่Token ที่copy เอาไว้
    $str = "Hello ทดสอบ Line notify "; //ข้อความที่ต้องการส่ง สูงสุด 1000 ตัวอักษร
    //$res111 = notify_message($str, $token);
    print_r($res111);
    

} else if ($load == "ConnectionInformation3") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $DVR = new DeliveryOrder();
    $DVR->setConn($ConnSL);
    $DVRS = $DVR->ConnectionInformation3();
    echo json_encode($DVRS);
} else {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $data = new ChartGraph();
    $data->setConn($ConnSL);
    $data = $data->GetData();
    echo json_encode($data);
}