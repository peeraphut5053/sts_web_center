<?php


include "../initial.php";
$temp = new ReplaceHtml("../../template/RPTARTRAN/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);
