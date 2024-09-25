<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/RPTITEM_RESULT/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);
