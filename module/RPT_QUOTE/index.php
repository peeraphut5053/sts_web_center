<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_QUOTE/index.html");

echo $temp->getReplace();
sqlsrv_close($ConnSL);