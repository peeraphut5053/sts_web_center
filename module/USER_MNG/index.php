<?php
//============= initial module =====
while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
//================== New Ver DASHBOARD ========
include "../initial.php";
$temp = new ReplaceHtml("../../template/USER_MNG/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);






