<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/STS_Custom_Data/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);