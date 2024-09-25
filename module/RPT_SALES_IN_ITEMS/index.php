

<?php


include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_SALES_IN_ITEMS/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);


