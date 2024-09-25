<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/AD_All_Temp/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);








