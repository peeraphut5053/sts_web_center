<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/RPTITEM_WH/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);
