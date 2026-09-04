<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/STS_Customer_Complaint/index.html");

echo $temp->getReplace();
sqlsrv_close($ConnSL);
sqlsrv_close($ConnWebApp);
