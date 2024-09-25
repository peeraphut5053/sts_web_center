

<?php



include "../initial.php";
$temp = new ReplaceHtml("../../template/APP_JOB_JobReceipt/index.html");

echo $temp->getReplace();
sqlsrv_close($ConnSL);


