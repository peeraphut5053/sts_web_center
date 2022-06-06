<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of _CommonFunction
 *
 * @author Admin
 */
class CommonFunction {

    var $StrConn = "";

    function setConn($c) {
        $this->StrConn = $c;
    }

//class MainFunction {

    function GetProjectCode() {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
            $link = "https";
        else
            $link = "http";
        $link .= "://";
        $link .= $_SERVER['HTTP_HOST'];
        $link .= $_SERVER['REQUEST_URI'];
        $links = explode("/", $link);
        $link_2 = explode("?", $links[4]);
        $prj_code = $link_2[1];
        return $prj_code;
    }

    function GetProjectInfo() {
        $CM = new CallModel();
        $CM->SyteLine_Models();
        $ProModel = new Project();
        $ProModel->setConn($this->StrConn);
        $prj_code = $this->GetProjectCode();
        $ProModel->GetProperties(" prj_code = '$prj_code' ") ;        
        return $ProModel->prj_description ;
    }

//}
    function ConvertPiecesToBundle($sched, $size) {
        $result = 0;
        $query = "SELECT TOP 1 *  FROM Calculate_PCS_to_BNDL WHERE sched ='$sched' AND size='$size' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        if (count($rs0 >= 1)) {
            $result = $rs0[0]["result"];
        }
        return $result;
    }

    function GetXlsFormList($form_id) {
        $result = 0;
        $query = "SELECT  *   FROM Xls_Form_Import ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        if (count($rs0 >= 1)) {
            $result = $rs0[0]["result"];
        }
        return $result;
    }

    function WebLog_Insert($LogModel ,$LogAction ,$LogMsg ){
        
           //====================STS INSERT LOG ====================
//        if(!isset($_SESSION) ){
//                     
//        }
//        session_start();  
        $Log_uid  = 1;
//        if($_SESSION["login_user_id"]){
//           $Log_uid =  SESSION["ss_user_login"] ;            
//        }
       
          
        $cSql = new SqlSrv();
        $LogInsert =   "EXEC	[dbo].[STS_WebLog_Insert] "
                . "@I_UserId = $Log_uid "
                . ", @I_Model = N'$LogModel' "
                . ", @I_Controller = N'' "
                . ", @I_Action = N'$LogAction' "
                . ", @I_MSG = N'$LogMsg' ";
         $cSql->SqlQuery($this->StrConn, $LogInsert);
         
         //========================================================
         
         
    }
}
