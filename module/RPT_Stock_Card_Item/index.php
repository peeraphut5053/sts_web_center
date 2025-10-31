<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_Stock_Card_Item/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);
?>