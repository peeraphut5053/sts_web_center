<?php
include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_QA_Document/index.html");
echo $temp->getReplace();
?>