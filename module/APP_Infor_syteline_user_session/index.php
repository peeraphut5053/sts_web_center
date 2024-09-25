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


$temp = new ReplaceHtml("../../template/APP_Infor_syteline_user_session/index.html");
$CallModel = new CallModel();
$CallModel->SyteLine_Models();

//======= get 1 value from model ============

//================== New Ver DASHBOARD ========
echo $temp->getReplace();
sqlsrv_close($ConnSL);
//================== New Ver DASHBOARD ========
