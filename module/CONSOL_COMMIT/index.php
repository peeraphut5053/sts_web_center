<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/CONSOL_COMMIT/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);




