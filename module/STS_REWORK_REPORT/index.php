<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/STS_REWORK_REPORT/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);