

<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_NEW_INVENTORY_Shipped_Date_Vs_Invoiced_Date/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);



