

<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/Material_Transachtions_by_PO/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);



