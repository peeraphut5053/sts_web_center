

<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/STS_TAG/index.html");

echo $temp->getReplace();
sqlsrv_close($ConnSL);


