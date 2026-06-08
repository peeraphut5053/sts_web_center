<?php

require_once "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_READY_STOCK/index.html");
echo $temp->getReplace();

?>
