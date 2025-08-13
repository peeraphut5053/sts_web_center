<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_CONTAINER_CONFIRM/index.html");

echo $temp->getReplace();
sqlsrv_close($ConnSL);