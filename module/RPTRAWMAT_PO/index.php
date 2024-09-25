<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/RPTRAWMAT_PO/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);
