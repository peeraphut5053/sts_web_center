<?php

include "../initial.php";

$temp = new ReplaceHtml("../../template/RPT_STS_CUSTOM_MAINRPT_ACCT/index.html");
echo $temp->getReplace();

sqlsrv_close($ConnSL);

?>