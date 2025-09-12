<?php
include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_PO/index.html");
echo $temp->getReplace();
?>