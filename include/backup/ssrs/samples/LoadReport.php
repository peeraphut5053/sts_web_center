<?php
$pathinc=  dirname(__DIR__) . '/library/SSRS/Report.php';
echo "$pathinc<br>" ;
// $folder = dirname(__DIR__) . './models/SHP/*.php' ;
//        foreach (glob($folder) as $filename) {
//            include $filename;
//        }
        
        
echo file_exists($pathinc) ;
//$AutoLoad = new AutoLoad();

//echo $AutoLoad->Init();
//require();
echo "<br>step1" ;
$options = array(
    'username' => 'sa',
    'password' => 'Sts@2017'
);
echo "<br>step2" ;
//

$ssrs = new \SSRS\Report('http://localhost/reportserver/', $options);

//$result = $ssrs->loadReport('/Reports/Reference_Report');
//
//$ssrs->setSessionId($result->executionInfo->ExecutionID);
//
//$output = $ssrs->render('HTML4.0'); // PDF | XML | CSV
//echo $output;