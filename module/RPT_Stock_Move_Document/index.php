<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_Stock_Move_Document/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);
?>