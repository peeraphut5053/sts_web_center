<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/ITEM_BATCH_UPDATE/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);







