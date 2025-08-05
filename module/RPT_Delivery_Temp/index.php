<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_Delivery_Temp/index.html");

echo $temp->getReplace();
sqlsrv_close($ConnSL);