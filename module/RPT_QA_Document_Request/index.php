<?php
include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_QA_Document_Request/index.html");
echo $temp->getReplace();
?>