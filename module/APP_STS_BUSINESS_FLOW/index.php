

<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/APP_STS_BUSINESS_FLOW/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);



