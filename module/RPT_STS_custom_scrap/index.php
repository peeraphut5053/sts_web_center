<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_STS_custom_scrap/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);
?>