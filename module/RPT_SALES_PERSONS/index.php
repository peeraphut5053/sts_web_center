<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_SALES_PERSONS/index.html");

echo $temp->getReplace();
sqlsrv_close($ConnSL);