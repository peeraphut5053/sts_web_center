<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_NEW_INVENTORY_BALANCE_Hot_Rolled_Coil_Ending_Balance/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);



