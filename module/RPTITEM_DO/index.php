<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/RPTITEM_DO/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);



