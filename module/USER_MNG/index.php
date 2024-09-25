<?php
//============= initial module =====

//================== New Ver DASHBOARD ========
include "../initial.php";
$temp = new ReplaceHtml("../../template/USER_MNG/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);






