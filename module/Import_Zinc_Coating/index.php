
<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/Import_Zinc_Coating/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);