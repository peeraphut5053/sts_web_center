<?php


include "../initial.php";

$temp = new ReplaceHtml("../../template/STS_Custom_Data/Import_Excel_In.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);