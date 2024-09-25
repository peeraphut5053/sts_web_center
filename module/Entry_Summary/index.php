<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/Entry_Summary/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);








