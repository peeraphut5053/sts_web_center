
<?php



include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_AD/index.html");

echo $temp->getReplace();
sqlsrv_close($ConnSL);