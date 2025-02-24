<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/Report_Repair/index.html");

echo $temp->getReplace();
sqlsrv_close($ConnSL);