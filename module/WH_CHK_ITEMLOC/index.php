<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/WH_CHK_ITEMLOC/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);






