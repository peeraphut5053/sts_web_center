<?php


include "../initial.php";
$temp = new ReplaceHtml("../../template/HOTROLL_CHECKING/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);








