<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_TOP_PURCHASE/index.html");

echo $temp->getReplace();
sqlsrv_close($ConnSL);
