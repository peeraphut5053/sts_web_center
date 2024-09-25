

<?php


include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_WORKBANCH_SHIPPING/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);


