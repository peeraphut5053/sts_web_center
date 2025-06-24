<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_INV_WEIGHT/index.html");

echo $temp->getReplace();
sqlsrv_close($ConnSL);