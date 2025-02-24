<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/Report_Repair_Check/index.html");

echo $temp->getReplace();
sqlsrv_close($ConnSL);