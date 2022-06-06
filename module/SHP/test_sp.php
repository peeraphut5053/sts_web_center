<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
$temp->setReplace("{crumb}", "");
$temp->setReplace("{pagename}", "Lists");
$temp->setReplace("{content}", $temp->getTemplate("./template/SHP/test_sp.html"));

//sqlsrv_close($ConnWebApp);

//$temp->setReplace("{docno}", $docno);
