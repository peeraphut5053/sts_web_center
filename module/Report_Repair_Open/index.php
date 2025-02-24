<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/Report_Repair_Open/index.html");

echo $temp->getReplace();
sqlsrv_close($ConnSL);