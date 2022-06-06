<?php
while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../../initial.php";
set_time_limit(0);
ini_set('memory_limit', '200M');
if ($load == "ajax") {
    $QtyCond = "";
    $txtItemFilter = $_POST["txtItemFilter"];
    $cbQty = $_POST["cbQty"];
    $cbQty == "1" ? $QtyCond = " AND qty_on_hand > 0 " : $QtyCond = "";

    $filter = " WHERE it.item + description like '%$txtItemFilter%' $QtyCond ";
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Item = new ItemSyteLine();
    $Item->setConn($ConnSL);

    $Items = $Item->GlobalSearchItem($filter);
    $CM = null;
    $GL = null;

    echo json_encode($Items);
}
