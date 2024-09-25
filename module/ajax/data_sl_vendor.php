<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}
include "./initial.php";
//echo $conn;
$vrnd = new SLVendor();
$vrnd->setConn($ConnSL);
if ($type == 1) {
    //Add to Dropdown select2
    $BuildRows = $vrnd->AjaxGetItemsDropdown();
} else {
    //Add to rows
    $BuildRows = $vrnd->AjaxGetRowsWithCond(" vd.vend_num LIKE '%" . $txtSearch . "%'  or name LIKE '%" . $txtSearch . "%'  ",$limit );
}


echo json_encode($BuildRows);
sqlsrv_close($conn);
