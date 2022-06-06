
<?php

class WebServiceConSL {

    var $ConnType = 0;
    public $strUserId = "sa";
    public $strPswd = "1234";
    public $strConfig = "Live_Test";
    public $WS_Ip = "http://172.18.1.33";

    //$ConnType == 0 ? $WS_Ip = "http://172.18.1.33" : $WS_Ip = "http://61.90.156.167";

    FUNCTION GetTokenSession() {
        $WS_Ip = $this->WS_Ip;
        $strUserId = $this->strUserId;
        $strPswd = $this->strPswd;
        $strConfig = $this->strConfig;
        $WS_Method = "CreateSessionToken";
        $WS_Url = "$WS_Ip/IDORequestService/IDOWebService.asmx";
        $params = "strUserId=$strUserId"
                . "&strPswd=$strPswd"
                . "&strConfig=$strConfig";
        $params_array = array(
            "strUserId" => $strUserId
            , "strPswd" => $strPswd
            , "strConfig" => $strConfig
        );
        $WS_Url_Full = "$WS_Url/$WS_Method?$params";
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
        //$newtoken = str_replace("<HTML><HEAD><TITLE>Length Required</TITLE> ", "", $newtoken);
        return $newtoken;
    }

    Function GetDataSet($Token, $strIDOName, $strPropertyList, $strFilter, $strOrderBy) {
        $WS_Ip = $this->WS_Ip;
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
        $WS_Method = "LoadDataSet";
        $WS_Url = "$WS_Ip/IDORequestService/IDOWebService.asmx";
        $WS_Url_Full = "$WS_Url/$WS_Method";
        $curl = curl_init($WS_Url_Full);
        curl_setopt($curl, CURLOPT_HEADER, false);
        //curl_setopt($curl, CURLOPT_HEADER, array('Content-Type: application/json'));

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);


        $json_response = curl_exec($curl);

//        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
//        curl_close($curl);
//        $response = json_decode($json_response, true);
//        return simplexml_load_string($json_response) ;
//        $array = htmlentities($json_response);
        return htmlentities($json_response);
    }

}

$strPropertyList = "Item='FC011N0003400-O100160M06000N'";
//$strPropertyList = "";


$GetTokenSession = new WebServiceConSL();
$token = $GetTokenSession->GetTokenSession();

$GetDataSet = $GetTokenSession->GetDataSet($token, "SLItems", "Item,Description", $strPropertyList, "");

//echo $token;
echo $GetDataSet;


