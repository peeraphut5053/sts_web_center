

<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_ACCT_COST_BY_DEPARTMENT/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);



