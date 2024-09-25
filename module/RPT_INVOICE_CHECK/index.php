

<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_INVOICE_CHECK/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);



