<?php
//============= initial module =====

//================== New Ver DASHBOARD ========
include "../initial.php";
$temp = new ReplaceHtml("../../template//index.html");

//================== New Ver DASHBOARD ========
echo $temp->getReplace();
sqlsrv_close($ConnSL);
//================== New Ver DASHBOARD ========





