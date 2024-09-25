

<?php



include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_DO_Checking/index.html");

echo $temp->getReplace();
sqlsrv_close($ConnSL);


