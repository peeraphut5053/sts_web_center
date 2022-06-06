<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<html>
    <head>
        <link href="css/bootstrap.min.css" rel="stylesheet"  type="text/css">   
        <script src="js/jquery.js"></script>  
    </head>
</html>
<?php
require_once("nusoap.php");

class WebServiceConSL {

    var $WS_IP_IN = "http://172.18.1.33/IDORequestService/IDOWebService.asmx";
    var $WS_IP_OUT = "http://61.90.156.167/IDORequestService/IDOWebService.asmx";

    FUNCTION GetTokenSession($Conn_Type, $strUserId, $strPswd, $strConfig) {
        $WS_Method = "CreateSessionToken";
        $WS_Url = "";
        if ($Conn_Type == 0) {
            $WS_Url = $this->WS_IP_IN;
        } else {
            $WS_Url = $this->$WS_IP_OUT;
        }


        $params = "strUserId=$strUserId"
                . "&strPswd=$strPswd"
                . "&strConfig=$strConfig";
        $params_array = array(
            "strUserId" => $strUserId
            , "strPswd" => $strPswd
            , "strConfig" => $strConfig
        );



        $WS_Url_Full = "$WS_Url/$WS_Method?$params";
        // echo $WS_Url_Full;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $WS_Url_Full,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));
        $token = "";
        $err = curl_error($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $token = curl_exec($curl);
        }
        curl_close($curl);

        $only_token = substr($token, 91);
        $only_token2 = substr_replace($only_token, "", -9);
        $oldtoken = $only_token2;
        $newtokenReplaceSlash = str_replace("/", "%2F", $oldtoken);
        $newtokenReplaceEquals = str_replace("+", "%2B", $newtokenReplaceSlash);
        $newtoken = str_replace("=", "%3D", $newtokenReplaceEquals);
        return $newtoken;
    }

    Function GetDataSet($Conn_Type, $Token, $strIDOName, $strPropertyList, $strFilter, $strOrderBy) {
        $WS_Method = "LoadDataSet";
        $WS_Url = "";
        if ($Conn_Type == 0) {
            $WS_Url = $this->WS_IP_IN;
        } else {
            $WS_Url = $this->$WS_IP_OUT;
        }
        $params_array = array(
            "strSessionToken" => $Token
            , "strIDOName" => $strIDOName
            , "strPropertyList" => $strPropertyList
            , "strFilter" => $strFilter
            , "strOrderBy" => $strOrderBy
            , "strPostQueryMethod" => ""
            , "iRecordCap" => -1
        );
        $params_json_enc = json_encode($params_array);
        $content = "";
        foreach ($params_array as $key => $value) {
            $content .= $key . '=' . $value . '&';
        }


        $WS_Url_Full = "$WS_Url/$WS_Method";


        $options = array(
            CURLOPT_RETURNTRANSFER => true, // return web page
            CURLOPT_HEADER => false, // don't return headers
            CURLOPT_FOLLOWLOCATION => true, // follow redirects
            CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
            CURLOPT_ENCODING => "", // handle compressed
            CURLOPT_USERAGENT => "test", // name of client
            CURLOPT_AUTOREFERER => true, // set referrer on redirect
            CURLOPT_CONNECTTIMEOUT => 120, // time-out on connect
            CURLOPT_TIMEOUT => 120, // time-out on response
            CURLOPT_POSTFIELDS => $content
        );
        $curl = curl_init($WS_Url_Full);
        curl_setopt_array($curl, $options);

//        curl_setopt($curl, CURLOPT_HEADER, true);
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($curl, CURLOPT_POST, true);
//        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);


        $response = curl_exec($curl);
        curl_close($curl);

        echo $response;
//        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
//        curl_close($curl);
//        $response = json_decode($json_response, true);
        // echo htmlentities( $json_response );
//        $oXML = new SimpleXMLElement($server_output);
//        header('Content-type: text/xml');
//        echo $oXML->asXML();
//        die();
//        $ResponseArr = simplexml_load_string($response);
//        echo $ResponseArr;
        //   return $ResponseArr;
    }
}

$SetConType = 0;
$UserId = "sa";
$Pswd = "11223344";
$Config = "Live";

$GetTokenSession = new WebServiceConSL();
$token = $GetTokenSession->GetTokenSession($SetConType, $UserId, $Pswd, $Config);

$Criteria = "Item LIKE 'FC0%'";
//$GetDataSet = $GetTokenSession->GetDataSet($SetConType, $token, "SLItems", "Item", $Criteria, "");
//
//
//============= TEST 2 =============//


require_once ("nusoap.php");
$WS_IP_IN = "http://172.18.1.33/IDORequestService/IDOWebService.asmx";
$WS_IP_OUT = "http://61.90.156.167/IDORequestService/IDOWebService.asmx";
$WS_Method = "LoadDataSet";
$WS_Url = "";
if ($SetConType == 0) {
    $WS_Url = $WS_IP_IN;
} else {
    $WS_Url = $WS_IP_OUT;
}
$params_array = array(
    "strSessionToken" => $token
    , "strIDOName" => "SLItems"
    , "strPropertyList" => "Item"
    , "strFilter" => $Criteria
    , "strOrderBy" => ''
    , "strPostQueryMethod" => ''
    , "iRecordCap" => -1
);



$client = new nusoap_client("$WS_Url?wsdl", true);

$data = $client->call($WS_Method, $params_array);
print_r($data);

echo "<hr>";
?>
<div class="container">
    <br>
    <br>
    <div class="well">
        <!--<h5>WebService Url : <?= $WS_Ip ?>/IDORequestService/IDOWebService.asmx</h5>-->
        <h5>User : <?= $UserId ?></h5>
        <h5>Password : <?= $Pswd ?> </h5>
        <h5>Configuration : <?= $Config ?></h5>
        <h5>Session Token :</h5>
        <h5><?= $token ?></h5> 
        <br>
        <h5>Criteria : <?= $Criteria ?></h5>
        <h5>LoadDataSet :</h5>
<?php
//echo $GetDataSet;
?>



    </div>
    <div class="row">

        <br>
<?php
// var_dump($GetDataSet);
?>
    </div>
</div>

<?php


