<?php


include "../initial.php";
$temp = new ReplaceHtml("../../template/WL/index.html");

echo $temp->getReplace();
sqlsrv_close($ConnSL);



