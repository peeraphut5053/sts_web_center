
<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/CER_DO/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);




