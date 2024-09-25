<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/RPTINV_OUTSTANDINGEX/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);





