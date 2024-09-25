

<?php



include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_OrderProcessing/index.html");

echo $temp->getReplace();
sqlsrv_close($ConnSL);


