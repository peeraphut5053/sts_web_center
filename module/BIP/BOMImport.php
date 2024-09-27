<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//include "./initial.php";

if (!isset($_POST["ajax"])) {
    $get_action = $_GET["get_action"];
    
    if ($get_action == "index") {
        
        $temp->setReplace("{content}", $temp->getTemplate("./template/BIP/index.html"));
//        $temp->setReplace("{trans_no}", $trans_no);
    } else if ($get_action == "add") {
        $tblLines = "";
        $CM = new CallModel();
        $CM->BIP_Models();
        $JobMatRoute = new JobMatRoute();
        $JobMatRoute->setConn($ConnWebApp);
        $trans_no = $JobMatRoute->GenNewTransNo();
//$temp = new ReplaceHtml("./template/CDT_Main.html");
        $temp->setReplace("{content}", $temp->getTemplate("./template/BIP/add.html"));
        $temp->setReplace("{trans_no}", $trans_no);

        $JobMatRoute = null;
    }
} else {
    include "./initial.php";
    $action = "";
    if (isset($_POST["action"])) {
        $action = $_POST["action"];
    }

    if ($action == "SaveJobRoute") {

        $xls_file = $_POST["xls_file"];
        $xls_sheet = $_POST["xls_sheet"];
        $trans_no = $_POST["trans_no"];
        $Item = $_POST["Item"];
        $WC = $_POST["WC"];
        $OP = $_POST["OP"];
        $std_time = $_POST["std_time"];

        $resultHeader = 0;
        $resultDetail = array();


        $JobMatRoute = new JobMatRoute();
        $JobMatRoute->setConn($ConnWebApp);
        $JobMatRoute->_trans_no = $trans_no;
        $JobMatRoute->_xls_file = $xls_file;
        $JobMatRoute->_xls_sheet = $xls_sheet;
        $x = count($Item);

        for ($i = 0; $i < $x; $i++) {
            $JobMatRoute->_detail_item = $Item[$i];
            $JobMatRoute->_detail_OP = $OP[$i];
            $JobMatRoute->_detail_WC = $WC[$i];
            $JobMatRoute->_detail_std_time = $std_time[$i];
            $JobMatRoute->SaveDetail();
        }
        $cSql = null;
        $JobMatRoute = null;

        echo 1;
    }
}

