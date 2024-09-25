<?php


include "../initial.php";
$temp = new ReplaceHtml("../../template/RPTINV_ITEM_IN/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);





