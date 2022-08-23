<?php
header("Access-Control-Allow-Origin: *");

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
require_once "../initial.php";
if ($load == "form") {
    
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
    //print_r($res111);
} else if ($load == "ConnectionInformation3") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $DVR = new DeliveryOrder();
    $DVR->setConn($ConnSL);
    $DVRS = $DVR->ConnectionInformation3();
    echo json_encode($DVRS);
} else if ($load == "LineNotification") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $DVR = new DeliveryOrder();
    $DVR->setConn($ConnSL);
    $DVRS = $DVR->ConnectionInformation2();
    //echo json_encode($DVRS);
    $CountUserOnline = count($DVR->ConnectionInformation());

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

// && $CountUserOnline >55
    if (count($DVRS) > 0 && (date("H:i:s") > "07:30:00" ) && (date("H:i:s") < "17:30:00" ) && $CountUserOnline > 58) {
        //q2cvv1Pjj4fw3VEJ6SqFZEe06e5bLhXLWL9FDEYJOl0
        define('LINE_API', "https://notify-api.line.me/api/notify");
        $token = "b5H1dHQVjRiGk0wJnIQX4JgUdeNx4HLjtQE9pYxd9O2";
        $userlist = "";
        for ($i = 0; $i < count($DVRS); $i++) {
            $userlist = $userlist . "User:" . $DVRS[$i]['UserName'] . ", เคลื่อนไหวล่าสุด :" . $DVRS[$i]['RecordDate'] . " รวมเวลา : " . $DVRS[$i]['minutediff'] . " นาที\n";
        }
        $str = "ขออนุญาติเตะ User ที่ไม่มีการเคลื่อนไหวในระบบ Syteline มากกว่า 15 นาที \n" . $userlist . "\n จำนวน User ที่ online ในระบบทั้งหมด " . $CountUserOnline;
        echo $str;
        echo date("H:i:s");
        $res = notify_message($str, $token);
        $DVR->STS_DELETE_SESSION_USER(15);
    }
} else if ($load == "minkick") {

    if (isset ($_POST['username'],$_POST['department'])) {
        $username = $_POST['username'];
        $department = $_POST['department'];
    }
    else{   
        $username ='';
        $department = '';
    }
    // ----
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $DVR = new DeliveryOrder();
    $DVR->setConn($ConnSL);
    $DVRS = $DVR->Log_Request_Session($username, $department);
    // ----

    // echo $DVRS;
    echo '<script>setTimeout(function(){ window.close(); }, 1000);</script>';
    echo ''
    . '<div style="font-size:3em;text-align:center;padding-top:10%;">'
    . ' <div style="border-style: outset;padding:50px;border-radius: 12px;margin: 2em;border: 2px solid green;">"ยืนยันการขอเข้าระบบสำเร็จ"</div>'
   
    . '<div><img src="sts_logo.jpg" alt="Cinque Terre" width="300" ></div>'
    . '</div>';
}
?>