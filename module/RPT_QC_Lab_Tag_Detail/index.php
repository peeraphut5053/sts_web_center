

<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_QC_Lab_Tag_Detail/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);