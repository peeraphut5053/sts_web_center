<?php
//============= initial module =====
foreach ($_GET as $key => $value) {
  $$key = trim($value);
}

foreach ($_POST as $key => $value) {
  $$key = trim($value);
}
//================== New Ver DASHBOARD ========
include "../initial.php";
$temp = new ReplaceHtml("../../template/APP_ADDMENU/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);



try {
  //$soap_client = new SoapClient("http://192.168.1.13/IDORequestService/IDOWebService.asmx?WSDL");
  $soap_client = new SoapClient("http://172.18.1.33/IDORequestService/IDOWebService.asmx?WSDL");
  // db test
      //http://192.168.1.13/IDORequestService/IDOWebService.asmx?WSDL
      // http://192.168.1.13/IDORequestService/IDOWebService.asmx?WSDL
      // db live
      // http://115.31.155.178/IDORequestService/IDOWebService.asmx?WSDL
      $user = 'sa';
      $pw = '11223344';
      $Siteparam = 'LIVE';

    $vec = array("strUserId"=>$user,"strPswd"=>$pw,"strConfig"=>$Siteparam);

    $quote = $soap_client->CreateSessionToken($vec);

    $test = $quote->CreateSessionTokenResult;

    if ($test == "InvalidCredentials") {
      echo "The user name or password you entered is invalid.";
    }
    elseif ($test == 'AccountLocked') {
      echo "Account Locked";
    }
    else {
      echo "Login Completed1";
    }
    echo "<br>";
    echo $test;
  } catch (SoapFault $exception) {

    $test = $exception->getMessage();
    // echo $test;
     echo $test;
    if ($test == "InvalidCredentials") {
      echo "The user name or password you entered is invalid.";
    }
    elseif ($test == 'AccountLocked') {
      echo "Account Locked";
    }
    else {
      echo "Login Completed2";
    }
  }
