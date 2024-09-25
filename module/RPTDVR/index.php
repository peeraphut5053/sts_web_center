<?php


include "../initial.php";
$temp = new ReplaceHtml("../../template/RPTDVR/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);



