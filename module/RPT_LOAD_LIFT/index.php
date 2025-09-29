<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_LOAD_LIFT/index.html");

echo $temp->getReplace();
sqlsrv_close($ConnSL);