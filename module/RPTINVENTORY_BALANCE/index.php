<?php


include "../initial.php";
$temp = new ReplaceHtml("../../template/RPTINVENTORY_BALANCE/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);



