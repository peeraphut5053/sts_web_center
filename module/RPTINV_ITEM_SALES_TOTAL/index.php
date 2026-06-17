

<?php
foreach (array_merge($_GET, $_POST) as $key => $data) {
    ${$key} = trim($data);
}
include "../initial.php";
$temp = new ReplaceHtml("../../template/RPTINV_ITEM_SALES_TOTAL/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);



