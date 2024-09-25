

<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_JOBPACKING/index.html");
//$temp = new ReplaceHtml("../../template/RPT_JOBPACKING/jobpacking_report/build/");
echo $temp->getReplace();
sqlsrv_close($ConnSL);



