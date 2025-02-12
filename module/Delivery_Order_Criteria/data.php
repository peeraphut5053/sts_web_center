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

if ($load == "Print") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $STS_Custom = new DeliveryOrder();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->GetDataDeliveryCriteria($type, $do_num_s, $do_num_e, $customer_c_s, $customer_c_e, $do_date_s, $do_date_e, $pick_date_s, $pick_date_e, $co_no_s, $co_no_e, $item_delivery_s, $item_delivery_e);
    echo json_encode($rs);
}