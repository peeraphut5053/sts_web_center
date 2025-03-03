<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_Job_Order_Report/index.html");

echo $temp->getReplace();
sqlsrv_close($ConnSL);