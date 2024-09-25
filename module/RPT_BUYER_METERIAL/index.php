

<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_BUYER_METERIAL/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);



