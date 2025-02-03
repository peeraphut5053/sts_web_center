<?php

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

include "../../initial.php";

if ($load == "Search") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $STS_Custom = new DeliveryOrder();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->GetDataDeliveryOptions();
    echo json_encode($rs);
} else if ($load == "SearchDx") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $STS_Custom = new DeliveryOrder();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->GetDataDeliveryDx($do_num);
    echo json_encode($rs);
} else if ($load == "Print") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $STS_Custom = new DeliveryOrder();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->GetDeliveryReport($do_num_start, $do_num_end, $first_line, $last_line, $customer_start, $customer_end, $inovice_start, $inovice_end);
    echo json_encode($rs);
} else if ($load == "SaveDelivery") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $STS_Custom = new DeliveryOrder();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->SaveDelivery($do_delivery, $line_delivery, $Uf_Container_num, $Uf_Container_zeal, $Uf_Container_size, $Uf_Container_blank_weight, $Uf_Container_car_num);
    echo json_encode($rs);
}
