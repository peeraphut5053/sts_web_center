<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_TAG_ID/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);