<?php


include "../initial.php";
$temp = new ReplaceHtml("../../template/REPORT_TagStatus/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);



