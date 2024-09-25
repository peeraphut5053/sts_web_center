

<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/RPTINV_ITEM_SALES_TOTAL/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);



