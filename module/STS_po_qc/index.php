

<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/STS_po_qc/index.html");

echo $temp->getReplace();
sqlsrv_close($ConnSL);


