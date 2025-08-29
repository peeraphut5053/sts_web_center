<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/APP_STORE_WITHDRAW/index.html");

echo $temp->getReplace();
sqlsrv_close($ConnSL);