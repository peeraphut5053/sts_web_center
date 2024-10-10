<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/STS_STORE_PASS/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);