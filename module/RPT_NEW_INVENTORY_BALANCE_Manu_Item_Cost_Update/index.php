

<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_NEW_INVENTORY_BALANCE_Manu_Item_Cost_Update/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);



