<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/STS_COUNT_STOCK/index.html");

echo $temp->getReplace();
sqlsrv_close($ConnSL);