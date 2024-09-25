

<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/STS_QTY_MOVE/index.html");

echo $temp->getReplace();
sqlsrv_close($ConnSL);


