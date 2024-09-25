<?php
//============= initial module =====

//================== New Ver DASHBOARD ========
include "../initial.php";
$temp = new ReplaceHtml("../../template/APP_A_REACT/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);



