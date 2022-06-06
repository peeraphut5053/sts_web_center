


<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../../initial.php";

if ($load == "RPT_NEW_INVENTORY_GROUP_BY_TRANS_TYPE") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $INVEN = new Inventory();
    $INVEN->setConn($ConnSL);
    $INVENS = $INVEN->RPT_NEW_INVENTORY_GROUP_BY_TRANS_TYPE($txtFromDate, $txtToDate, $txtref_num, $txtItem, $txtw_c, $FinishQty, $IssueQty,$flagAllItem);
    $CM = null;
    $INVEN = null;
    echo json_encode($INVENS);
}

