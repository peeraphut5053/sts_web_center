<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_Export_Invoice_All_Temp/index.html");

echo $temp->getReplace();
sqlsrv_close($ConnSL);