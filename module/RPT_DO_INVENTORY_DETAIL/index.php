<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_DO_INVENTORY_DETAIL/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);



