<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/STS_REWORK_LINE/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);