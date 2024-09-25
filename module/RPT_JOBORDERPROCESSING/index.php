

<?php


include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_JOBORDERPROCESSING/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);


