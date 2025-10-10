<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/STS_RETURN_ITEM/index.html");

echo $temp->getReplace();
sqlsrv_close($ConnSL);