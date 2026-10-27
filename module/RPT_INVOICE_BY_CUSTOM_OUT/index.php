<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_INVOICE_BY_CUSTOM_OUT/index.html");

echo $temp->getReplace();
sqlsrv_close($ConnSL);
?>

