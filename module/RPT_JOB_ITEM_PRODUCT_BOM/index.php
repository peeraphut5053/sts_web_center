<?php

//============= initial module =====
foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}
//================== New Ver DASHBOARD ========
include "../initial.php";


$temp = new ReplaceHtml("../../template/RPT_JOB_ITEM_PRODUCT_BOM/index.html");
$CallModel = new CallModel();
$CallModel->SyteLine_Models();

//======= get 1 value from model ============

//================== New Ver DASHBOARD ========
echo $temp->getReplace();
sqlsrv_close($ConnSL);
//================== New Ver DASHBOARD ========
