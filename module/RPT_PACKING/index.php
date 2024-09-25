<?php


include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_PACKING/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);



