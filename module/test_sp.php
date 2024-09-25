<?php

$temp->setReplace("{crumb}", "");
$temp->setReplace("{pagename}", "Lists");
$temp->setReplace("{content}", $temp->getTemplate("./template/test_sp.html"));

//sqlsrv_close($conn);

//$temp->setReplace("{docno}", $docno);
