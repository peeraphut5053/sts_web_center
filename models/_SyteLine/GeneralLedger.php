<?php

class GeneralLedger {

    var $StrConn = "";
    public $_StartDate = "";
    public $_EndDate = "";
    public $_Acct = array();
    public $_AcctDiary = "";
    public $_Year = "";
    public $_Month = "";
    public $_Month2 = "";
    public $_Saleside = "";
    public $_Cust = array();

    function setConn($c) {
        $this->StrConn = $c;
    }

    Function GetRows() {
        $Acct = $this->_Acct;
        $StartDate = $this->_StartDate . " 00:00:00";
        $EndDate = $this->_EndDate . " 23:59:59";
        $CriteriaAcct = "";
        $cSql = new SqlSrv();
        $query = "SELECT * FROM V_WebApp_GL  WHERE 1=1  "
                . "AND (trans_date BETWEEN '$StartDate' AND '$EndDate' )  ";
        if (count($Acct) == 1) {
            if (isset($Acct[0])) {
                if (strpos($Acct[0], 'ALL') !== false) {
                    $ep = explode("_", $Acct[0]);
                    $query = $query . "AND ( acct like '%" . $ep[1] . "%' ) ";
                } else {
                    $query = $query . "AND ( acct = '" . $Acct[0] . "' ) ";
                }
            }
        } else {
            $AllAcct = "";
            foreach ($Acct as $ii => $rr) {
                if (strpos($rr, 'ALL') !== false) {
                    $ep = explode("_", $rr);
                    $AllAcct = $AllAcct . " acct like '%" . $ep[1] . "%' OR ";
                } else {
                    $AllAcct = $AllAcct . " acct = '$rr' OR ";
                }
            }
            $AllAcct = substr($AllAcct, 0, -3);
            //$query = $query . " AND ($AllAcct) ";
        }

        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function GetRowsCollectionDay() {
        $Cust = $this->_Cust;
        $StartDate = $this->_StartDate . " 00:00:00";
        $EndDate = $this->_EndDate . " 23:59:59";
        $CriteriaAcct = "";
        $cSql = new SqlSrv();
        $query = "SELECT * FROM V_WebApp_CollectionReport  WHERE 1=1  "
                . "AND (inv_date BETWEEN '$StartDate' AND '$EndDate' )  ";
        if (count($Cust) == 1) {
            if (isset($Cust[0])) {
                if (strpos($Cust[0], 'ALL') !== false) {
                    $ep = explode("_", $Cust[0]);
                    $query = $query . "AND ( cust_num like '%" . $ep[1] . "%' ) ";
                } else {
                    $query = $query . "AND ( cust_num = '" . $Cust[0] . "' ) ";
                }
            }
        } else {
            /*
              $AllCust = "";
              foreach ($Cust as $ii => $rr) {
              if (strpos($rr, 'ALL') !== false) {
              $ep = explode("_", $rr);
              $AllCust = $AllCust . " cust_num like '%" . $ep[1] . "%' OR ";
              } else {
              $AllCust = $AllCust . " cust_num = '$rr' OR ";
              }
              }
              $AllCust = substr($AllCust, 0, -3);

             */
            $query = $query;
        }
        $query .= " ORDER BY inv_date ";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);

        //====================STS INSERT LOG ====================
//        if(!isset($_SESSION) ){
//                     
//        }
//        session_start();  
        /*
          if ($_SESSION["login_user_id"]) {
          $Log_uid = SESSION["login_user_id"];
          } else {
          $Log_uid = 1;
          }
          $LogController = "GetRowsCollectionDay";
          $LogModel = "GeneralLedger";
          $LogMsg = "Query Data";
          $LogAction = "Query";
          $cSql = new SqlSrv();
          $LogInsert = "EXEC	[dbo].[STS_WebLog_Insert] "
          . "@I_UserId = $Log_uid "
          . ", @I_Model = N'$LogModel' "
          . ", @I_Controller = N'$LogController' "
          . ", @I_Action = N'$LogAction' "
          . ", @I_MSG = N'$LogMsg' ";
          $cSql->SqlQuery($this->StrConn, $LogInsert);
         */
        //========================================================


        return $rs;
    }

    Function GetRows_SP($selYear, $selMonth, $selMonth2, $Acct) {

//        $Accts ="" ;
//        foreach($Acct as $ii=>$rr ){
//            $Accts=$Accts.$rr.",";
//        }

        $distance = $selMonth2 - $selMonth;



        $callSP = 'Exec SP_WebApp_GeneralLedger @distance=?,@year=?,@month=?,@acct=?';
        $params = array(0, $selYear, $selMonth, $Acct);
        $stmt = sqlsrv_query($this->StrConn, $callSP, $params);
        if ($stmt === false) {
            return "Error in executing statement 3.\n";
            die(print_r(sqlsrv_errors(), true));
        }
        $ArrLG = array();
        $ArrLG2 = array();

        //array_push($ArrLG, $ArrLG);

        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $ArrLG2["trans_num"] = $row['trans_num'];
            $ArrLG2["trans_date"] = $row['trans_date']->format('Y-m-d H:i:s');
            $ArrLG2["acct"] = $row['acct'];
            $ArrLG2["ref"] = $row['ref'];
            $ArrLG2["check_num"] = $row['check_num'];
            $ArrLG2["for_amount"] = $row['for_amount'];
            $ArrLG2["debit"] = $row['debit'];
            $ArrLG2["credit"] = $row['credit'];
            $ArrLG2["accumulate"] = $row['accumulate'];
            $ArrLG2["note"] = $row['note'];
            $ArrLG2["ManualAccu"] = $row['ManualAccu'];
            array_push($ArrLG, $ArrLG2);
        }
        sqlsrv_free_stmt($stmt);
        





        //====================STS INSERT LOG ====================
        $this->WebLog_Insert('GeneralLedger', 'GetRows_SP', 'Query');
        //====================STS INSERT LOG ====================

        return $ArrLG;
    }

    function WebLog_Insert($LogModel, $LogAction, $LogMsg) {
        //====================STS INSERT LOG ====================
        //$Log_uid = SESSION["login_username"] ;
//        if (isset($_SESSION["login_username"])) {
//            $Log_uid = SESSION["login_username"];
//        } else {
//            $Log_uid = 1;
//        }

        $Log_uid = 1;

        $cSql = new SqlSrv();
        $LogInsert = "EXEC	[dbo].[STS_WebLog_Insert] "
                . "@I_UserId = $Log_uid "
                . ", @I_Model = N'$LogModel' "
                . ", @I_Controller = N'' "
                . ", @I_Action = N'$LogAction' "
                . ", @I_MSG = N'$LogMsg' ";
        $cSql->SqlQuery($this->StrConn, $LogInsert);

        //========================================================
    }

    Function GetRowsGLDairy() {

        $_AcctDiary = $this->_AcctDiary;
        $StartDate = $this->_StartDate;
        $EndDate = $this->_EndDate;
        $query = "SELECT top 1000 vend_cust_name,vend_num,site_ref,trans_num,acct , ISNULL(acct_unit1,'') as acct_unit1 ,format(trans_date,'dd/MM/yyyy')  as trans_date , description "
                . ",CASE WHEN dom_amount > 0 THEN dom_amount ELSE 0 END as Debit_Domestic "
                . ",CASE WHEN dom_amount < 0 THEN dom_amount ELSE 0 END as Credit_Domestic "
                . ",exch_rate,CASE WHEN for_amount > 0 THEN for_amount ELSE 0 END as Debit_Foreign "
                . ",CASE WHEN for_amount < 0 THEN for_amount ELSE 0 END as Credit_Foreign "
                . ",ref ,from_id as Posted_From ,ISNULL(NoteContent ,'') as NoteContent "
                . "from V_WebApp_GL where ref like '%" . $_AcctDiary . "%'  ";
        if ($StartDate != "" && $EndDate) {
            $query = $query . "AND (trans_date BETWEEN '$StartDate' AND '$EndDate' )  ";
        }
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

}
