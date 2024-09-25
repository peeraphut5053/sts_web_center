<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/RPTDO_PENDING/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);



