<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_NEW_INVENTORY_BALANCE_Stock_Card_by_Invoice_No/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);



