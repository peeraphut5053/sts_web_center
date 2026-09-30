<?php
include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_HOT_ROLLED_COIL_OUT/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);
