<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
require_once "../initial.php";
//$load = "RPT_ACCT_COST_BY_DEPARTMENT";
//$txtFromDate = '';
//$txtToDate = '';


if ($load == "RPT_ACCT_COST_BY_DEPARTMENT") {

    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Cost = new Costing();
    $Cost->setConn($ConnSL);
    $Cost = $Cost->RPT_ACCT_COST_BY_DEPARTMENT($txtFromDate, $txtToDate);
    $Cost2 = new Costing();
    $Cost2->setConn($ConnSL);
    $Cost2 = $Cost2->RPT_ACCT_COST_BY_DEPARTMENT2($txtFromDate, $txtToDate);
    $Cost3 = new Costing();
    $Cost3->setConn($ConnSL);
    $Cost3 = $Cost3->RPT_ACCT_COST_BY_DEPARTMENT_Weight($txtFromDate, $txtToDate);

    $total_DL_1 = 0;
    $total_DL_2 = 0;
    $total_DL_3 = 0;
    $total_DL_4 = 0;
    $total_DL_5 = 0;
    $total_DL_6 = 0;
    $total_DL_7 = 0;
    $total_DL_8 = 0;
    $total_DL_9 = 0;
    $total_DL_10 = 0;
    $total_DL_11 = 0;
    $total_DL_12 = 0;
    $total_DL_13 = 0;
    $total_DL_14 = 0;
    $total_DL_15 = 0;
    $total_DL_16 = 0;
    $total_DL_17 = 0;
    for ($i = 0; $i < 7; $i++) {
        $total_DL_1 = $total_DL_1 + $Cost[$i]['ตัดแบ่ง sts'];
        $total_DL_2 = $total_DL_2 + $Cost[$i]['ตัดแบ่ง wng'];
        $total_DL_3 = $total_DL_3 + $Cost[$i]['ฟอร์มมิ่ง sts'];
        $total_DL_4 = $total_DL_4 + $Cost[$i]['ฟอร์มมิ่ง wng'];
        $total_DL_5 = $total_DL_5 + $Cost[$i]['ฟอร์มมิ่ง-JHP'];
        $total_DL_6 = $total_DL_6 + $Cost[$i]['เตาชุบ'];
        $total_DL_7 = $total_DL_7 + $Cost[$i]['ต๊าปเกลียว sts'];
        $total_DL_8 = $total_DL_8 + $Cost[$i]['ต๊าปเกลียว wng'];
        $total_DL_9 = $total_DL_9 + $Cost[$i]['พ่นสี sts'];
        $total_DL_10 = $total_DL_10 + $Cost[$i]['พ่นสี wng'];
        $total_DL_11 = $total_DL_11 + $Cost[$i]['พ่นสี-KRC'];
        $total_DL_12 = $total_DL_12 + $Cost[$i]['มัดท่อ sts'];
        $total_DL_13 = $total_DL_13 + $Cost[$i]['มัดท่อ wng'];
        $total_DL_14 = $total_DL_14 + $Cost[$i]['รีดลดความหนา'];
        $total_DL_15 = $total_DL_15 + $Cost[$i]['แผนกเครื่องรีดหลังคา'];
        $total_DL_16 = $total_DL_16 + $Cost[$i][' Prefabrication'];
        $total_DL_17 = $total_DL_17 + $Cost[$i]['Total'];
    }

    $total_DL = [
        'acct' => '52999DL',
        'description' => '',
        'ตัดแบ่ง sts' => "$total_DL_1",
        'ตัดแบ่ง wng' => "$total_DL_2",
        'ฟอร์มมิ่ง sts' => "$total_DL_3",
        'ฟอร์มมิ่ง wng' => "$total_DL_4",
        'ฟอร์มมิ่ง-JHP' => "$total_DL_5",
        'เตาชุบ' => "$total_DL_6",
        'ต๊าปเกลียว sts' => "$total_DL_7",
        'ต๊าปเกลียว wng' => "$total_DL_8",
        'พ่นสี sts' => "$total_DL_9",
        'พ่นสี wng' => "$total_DL_10",
        'พ่นสี-KRC' => "$total_DL_11",
        'มัดท่อ sts' => "$total_DL_12",
        'มัดท่อ wng' => "$total_DL_13",
        'รีดลดความหนา' => "$total_DL_14",
        'แผนกเครื่องรีดหลังคา' => "$total_DL_15",
        ' Prefabrication' => "$total_DL_16",
        'Total' => "$total_DL_17",
    ];

    array_push($Cost, $total_DL);

    for ($i = 0; $i < 36; $i++) {
        array_push($Cost, $Cost2[$i]);
    }




    $total_OH_1 = 0;
    $total_OH_2 = 0;
    $total_OH_3 = 0;
    $total_OH_4 = 0;
    $total_OH_5 = 0;
    $total_OH_6 = 0;
    $total_OH_7 = 0;
    $total_OH_8 = 0;
    $total_OH_9 = 0;
    $total_OH_10 = 0;
    $total_OH_11 = 0;
    $total_OH_12 = 0;
    $total_OH_13 = 0;
    $total_OH_14 = 0;
    $total_OH_15 = 0;
    $total_OH_16 = 0;
    $total_OH_17 = 0;
//    echo '<pre>';
    //   print_r($Cost);
//    echo '<pre>';
    for ($i = 0; $i < 36; $i++) {
        $total_OH_1 = $total_OH_1 + $Cost2[$i]['ตัดแบ่ง sts'];
        $total_OH_2 = $total_OH_2 + $Cost2[$i]['ตัดแบ่ง wng'];
        $total_OH_3 = $total_OH_3 + $Cost2[$i]['ฟอร์มมิ่ง sts'];
        $total_OH_4 = $total_OH_4 + $Cost2[$i]['ฟอร์มมิ่ง wng'];
        $total_OH_5 = $total_OH_5 + $Cost2[$i]['ฟอร์มมิ่ง-JHP'];
        $total_OH_6 = $total_OH_6 + $Cost2[$i]['เตาชุบ'];
        $total_OH_7 = $total_OH_7 + $Cost2[$i]['ต๊าปเกลียว sts'];
        $total_OH_8 = $total_OH_8 + $Cost2[$i]['ต๊าปเกลียว wng'];
        $total_OH_9 = $total_OH_9 + $Cost2[$i]['พ่นสี sts'];
        $total_OH_10 = $total_OH_10 + $Cost2[$i]['พ่นสี wng'];
        $total_OH_11 = $total_OH_11 + $Cost2[$i]['พ่นสี-KRC'];
        $total_OH_12 = $total_OH_12 + $Cost2[$i]['มัดท่อ sts'];
        $total_OH_13 = $total_OH_13 + $Cost2[$i]['มัดท่อ wng'];
        $total_OH_14 = $total_OH_14 + $Cost2[$i]['รีดลดความหนา'];
        $total_OH_15 = $total_OH_15 + $Cost2[$i]['แผนกเครื่องรีดหลังคา'];
        $total_OH_16 = $total_OH_16 + $Cost2[$i][' Prefabrication'];
        $total_OH_17 = $total_OH_17 + $Cost2[$i]['Total'];
    }



    $total_OH = [
        'acct' => '53999DL',
        'description' => '',
        'ตัดแบ่ง sts' => "$total_OH_1",
        'ตัดแบ่ง wng' => "$total_OH_2",
        'ฟอร์มมิ่ง sts' => "$total_OH_3",
        'ฟอร์มมิ่ง wng' => "$total_OH_4",
        'ฟอร์มมิ่ง-JHP' => "$total_OH_5",
        'เตาชุบ' => "$total_OH_6",
        'ต๊าปเกลียว sts' => "$total_OH_7",
        'ต๊าปเกลียว wng' => "$total_OH_8",
        'พ่นสี sts' => "$total_OH_9",
        'พ่นสี wng' => "$total_OH_10",
        'พ่นสี-KRC' => "$total_OH_11",
        'มัดท่อ sts' => "$total_OH_12",
        'มัดท่อ wng' => "$total_OH_13",
        'รีดลดความหนา' => "$total_OH_14",
        'แผนกเครื่องรีดหลังคา' => "$total_OH_15",
        ' Prefabrication' => "$total_OH_16",
        'Total' => "$total_OH_17",
    ];
    array_push($Cost, $total_OH);
    array_push($Cost, $Cost3[0]);
    $total_Adjust_weight = [
        'acct' => 'Weight Adjust ',
        'description' => '',
        'ตัดแบ่ง sts' => "",
        'ตัดแบ่ง wng' => "",
        'ฟอร์มมิ่ง sts' => "",
        'ฟอร์มมิ่ง wng' => "",
        'ฟอร์มมิ่ง-JHP' => "",
        'เตาชุบ' => "",
        'ต๊าปเกลียว sts' => "",
        'ต๊าปเกลียว wng' => "",
        'พ่นสี sts' => "",
        'พ่นสี wng' => "",
        'พ่นสี-KRC' => "",
        'มัดท่อ sts' => "",
        'มัดท่อ wng' => "",
        'รีดลดความหนา' => "",
        'แผนกเครื่องรีดหลังคา' => "",
        ' Prefabrication' => "",
        'Total' => "",
    ];
    array_push($Cost, $total_Adjust_weight);
    $total_weight = [
        'acct' => 'Weight Total ',
        'description' => '',
        'ตัดแบ่ง sts' => $Cost3[0]['ตัดแบ่ง sts'],
        'ตัดแบ่ง wng' => $Cost3[0]['ตัดแบ่ง wng'],
        'ฟอร์มมิ่ง sts' => $Cost3[0]['ฟอร์มมิ่ง sts'],
        'ฟอร์มมิ่ง wng' => $Cost3[0]['ฟอร์มมิ่ง wng'],
        'ฟอร์มมิ่ง-JHP' => $Cost3[0]['ฟอร์มมิ่ง-JHP'],
        'เตาชุบ' => $Cost3[0]['เตาชุบ'],
        'ต๊าปเกลียว sts' => $Cost3[0]['ต๊าปเกลียว sts'],
        'ต๊าปเกลียว wng' => $Cost3[0]['ต๊าปเกลียว wng'],
        'พ่นสี sts' => $Cost3[0]['พ่นสี sts'],
        'พ่นสี wng' => $Cost3[0]['พ่นสี wng'],
        'พ่นสี-KRC' => $Cost3[0]['พ่นสี-KRC'],
        'มัดท่อ sts' => $Cost3[0]['มัดท่อ sts'],
        'มัดท่อ wng' => $Cost3[0]['มัดท่อ wng'],
        'รีดลดความหนา' => $Cost3[0]['รีดลดความหนา'],
        'แผนกเครื่องรีดหลังคา' => $Cost3[0]['แผนกเครื่องรีดหลังคา'],
        ' Prefabrication' => $Cost3[0][' Prefabrication'],
        'Total' => $Cost3[0]['Total'],
    ];
    array_push($Cost, $total_weight);

    $departments = [
        'ตัดแบ่ง sts',
        'ตัดแบ่ง wng',
        'ฟอร์มมิ่ง sts',
        'ฟอร์มมิ่ง wng',
        'ฟอร์มมิ่ง-JHP',
        'เตาชุบ',
        'ต๊าปเกลียว sts',
        'ต๊าปเกลียว wng',
        'พ่นสี sts',
        'พ่นสี wng',
        'พ่นสี-KRC',
        'มัดท่อ sts',
        'มัดท่อ wng',
        'รีดลดความหนา',
        'แผนกเครื่องรีดหลังคา',
        ' Prefabrication',
        'Total',
    ];

    $xDL = [
        'acct' => 'xDL / Kg ',
        'description' => '',
    ];

    $xOH = [
        'acct' => 'xOH /Kg ',
        'description' => '',
    ];

    $xTotal = [
        'acct' => 'xTotal',
        'description' => '',
    ];

    foreach ($departments as $dept) {
        $weight = (isset($Cost3[0][$dept]) && is_numeric($Cost3[0][$dept])) ? (float)$Cost3[0][$dept] : 0;

        $dl_val = (isset($total_DL[$dept]) && is_numeric($total_DL[$dept])) ? (float)$total_DL[$dept] : 0;
        $xDL[$dept] = ($weight != 0.0) ? ($dl_val / $weight) : 0;

        $oh_val = (isset($total_OH[$dept]) && is_numeric($total_OH[$dept])) ? (float)$total_OH[$dept] : 0;
        $xOH[$dept] = ($weight != 0.0) ? ($oh_val / $weight) : 0;

        $xTotal[$dept] = $xDL[$dept] + $xOH[$dept];
    }

    array_push($Cost, $xDL);
    array_push($Cost, $xOH);
    array_push($Cost, $xTotal);

//    echo '<pre>';
//    print_r($total_weight);
//    echo '</pre>';
    echo json_encode($Cost);
} 


