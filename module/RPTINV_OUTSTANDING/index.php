<?php


include "../initial.php";
$temp = new ReplaceHtml("../../template/RPTINV_OUTSTANDING/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);





