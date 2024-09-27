<?php
while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_Stock_Order_Planning/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);
?>


