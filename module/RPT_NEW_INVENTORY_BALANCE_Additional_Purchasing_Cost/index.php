<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_NEW_INVENTORY_BALANCE_Additional_Purchasing_Cost/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);



