<?php
//============= initial module =====
while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
//================== New Ver DASHBOARD ========
include "../initial.php";
$temp = new ReplaceHtml("../../template/APP_A_REACT/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);



