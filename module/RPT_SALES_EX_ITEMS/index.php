

<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_SALES_EX_ITEMS/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);


