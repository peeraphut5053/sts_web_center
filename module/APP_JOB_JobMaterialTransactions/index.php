

<?php


include "../initial.php";
$temp = new ReplaceHtml("../../template/APP_JOB_JobMaterialTransactions/index.html");

echo $temp->getReplace();
sqlsrv_close($ConnSL);


