<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");
foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    if (is_array($value)) {
        // ถ้าเป็น array ให้วนลูปอีกครั้งเพื่อ trim แต่ละ element
        foreach ($value as $subKey => $subValue) {
            $$key[$subKey] = trim($subValue);
        }
    } else {
        $$key = trim($value);
    }
}
require_once "../initial.php";
$CallModel = new CallModel();
$CallModel->SyteLine_Models();
$CallModelObj = new JOBORDER();
$CallModelObj->setConn($ConnSL);


if ($load == "GetDataJob") {
    $GetDataJob = $CallModelObj->GetDataJob($job);
    echo json_encode($GetDataJob);
} else if ($load == "GetDataTag") {
    $GetDataTag = $CallModelObj->GetDataTag($id,$job);
    echo json_encode($GetDataTag);
} else if ($load == "IssueProcess") {
    $IdArr = $_POST['tIdGridCol'];
    $ItemArr = $_POST['tItemGridCol'];
    $LotArr = $_POST['tLotGridCol'];
    $LocArr = $_POST['tLocGridCol'];
    $GetDataTag = $CallModelObj->IssueProcess($IdArr, $ItemArr, $LotArr, $LocArr, $Wc, $Job,$username);
    echo json_encode($GetDataTag);
} else if ($load == "GetBarType") {
    $GetBarType = $CallModelObj->GetBarType($barcode);
    echo json_encode($GetBarType);
} else if ($load == "Getmatltran_mst") {
    $Getmatltran_mst = $CallModelObj->Getmatltran_mst($job);
    echo json_encode($Getmatltran_mst);
} else if ($load == 'GetJob') {
    $GetDataJob = $CallModelObj->GetJob($job);
    echo json_encode($GetDataJob);
} else if ($load == 'GetTag') {
    $GetDataJob = $CallModelObj->GetTag($job, $tag);
    echo json_encode($GetDataJob);
} else if ($load == 'MatProcess') {
    if (empty($tag) && empty($job)) {
        // อ่าน raw input จาก request body
        $rawInput = file_get_contents('php://input');
        $data = json_decode($rawInput, true); // true เพื่อให้เป็น associative array
        $tag = $data['data'] ?? '';
    }

    // loop tag array.length
    foreach ($tag as $key => $value) {
        $GetDataJob = $CallModelObj->MaterialProcess($data["job"], $data["suffix"], $value["item"], $data["operNum"], $value["qty1"], $value["qty2"], $value["lot"], $value["loc"], $value["DocRef"]);
        
    }

    echo json_encode($GetDataJob);
}


  /*get request payload

    // ถ้า axios ส่งข้อมูลแบบ application/json (ขึ้นอยู่กับการตั้งค่า API.post)
    if (empty($tag) && empty($job)) {
        // อ่าน raw input จาก request body
        $rawInput = file_get_contents('php://input');
        $data = json_decode($rawInput, true); // true เพื่อให้เป็น associative array
        $tag = $data['data'] ?? '';
        $job = $data['job'] ?? '';
    }

    // echo array('tag' => $tag, 'job' => $job);

    echo json_encode(array('tag' => $tag));

    // ตัวอย่าง: ส่งข้อมูลที่รับได้กลับไป
    $response = [
        'status' => 'success',
        'load' => $load,
        'tag' => $tag,
        'job' => $job
    ];

    // ตั้งค่า header และส่ง JSON response
    header('Content-Type: application/json');
    echo json_encode($response);