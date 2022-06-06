<?php

//=========== Global Scope Variable ===========//
$host = $_SERVER['HTTP_HOST'];

//$root = $_SERVER['DOCUMENT_ROOT'];
//$self = $_SERVER['PHP_SELF'];
//$rootpath = $root . "/" . $path;
//include($rootpath . "/include/config.php");
//include($rootpath . "/include/sqlConn.php");
//===========================================//
class JobMatRoute {

//========= Inside Class Variable ===========//
    var $StrConn = "";
    public $_trans_no = "";
    public $_xls_file = "";
    public $_xls_sheet = "";
    public $_date_time = "";
    public $_user_id = "";
    
    public $_detail_item = "";
    public $_detail_OP = "";
    public $_detail_WC = "";
    public $_detail_std_time= "";
    function setConn($c) {
        $this->StrConn = $c;
    }

//============================================//
//========= Initial Class Utilities ===========//
    function GetDataArray_SL($sql) {
        $cSql = new SqlSrv();
        $GlobConn = $GLOBALS["SecondaryConnection"];
        $rs = $cSql->SqlQuery($GlobConn, $sql);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

//==============================================//
    Function UpdateUfCoilNo($lot, $sts_no, $coil_no, $item, $realweights, $realwidths, $realthicks) {

        $cSql = new SqlSrv();
        $GlobConn = $GLOBALS["conn_sl"];
        $sql = "UPDATE lot_mst SET Uf_coil_no ='$coil_no'  , Uf_act_weight=$realweights ,Uf_act_width=$realwidths , uf_act_tickness = $realthicks ,sts_no = '$sts_no'  WHERE lot = '$lot' AND item = '$item' ";
        $rs = $cSql->IsUpDel($GlobConn, $sql);

        return $rs;
    }

    Function SaveHeader() {
        $cSql = new SqlSrv();
        $user_id = $_SESSION["login_user_id"];
        $sql = "INSERT INTO BOM_Import_jobroute_header (trans_no,xls_file,xls_sheet,date_time,user_id) "
                . "VALUES "
                . "('" . $this->_trans_no . "','" . $this->_xls_file . "','" . $this->_xls_sheet . "',GETDATE(),$user_id) ";
        $rs = $cSql->IsUpDel($this->StrConn, $sql);
        return $rs;
    }

    Function SaveDetail() {
        $cSql = new SqlSrv();
        $user_id = $_SESSION["login_user_id"];
        $sql = "INSERT INTO BOM_Import_jobroute_detail (trans_no,item,op,wc,std_time) "
                . "VALUES "
                . "('" . $this->_trans_no . "','" . $this->_detail_item . "'," . $this->_detail_OP . ",'" . $this->_detail_WC . "'," . $this->_detail_std_time . ") ";
        $rs = $cSql->IsUpDel($this->StrConn, $sql);
        return $rs;
    }

    
    function GenNewTransNo() {

//        $SqlConnectionInfo = $GLOBALS["var"]["mysql"];
        $cSql = new SqlSrv();
        $RunningDigit = 4;
        $CurrDate = date("ymd");
        $RunningInit = "0001";
        $sql = "SELECT TOP 1 trans_no FROM  BOM_Import_jobroute_header   ORDER BY date_time  DESC , trans_no DESC  ";
        $rs = $cSql->SqlQuery($this->StrConn, $sql);
        array_splice($rs, count($rs) - 1, 1);
        $tmpDocNo = "";
        $tmpDocNoCutPrefix = "";
        $tmpDocNoDate = "";
        $tmpDocNoCutDate = "";
        $val = "";

        if (count($rs) < 1) {
            return $CurrDate . $RunningInit;
        } else {
            $tmpDocNo = trim($rs[0]["trans_no"]);
            $tmpDocNoDate = substr($tmpDocNo, 0, 6);
            $tmpDocNoCutDate = substr($tmpDocNo, 6, 10);

            if ($CurrDate == $tmpDocNoDate) {
                $val = str_pad(strval($tmpDocNoCutDate) + 1, $RunningDigit, '0', STR_PAD_LEFT);
                return $CurrDate . "" . $val;
            } else {
                return $CurrDate . $RunningInit;
            }
        }
        $cSql = null;
//================Gen Head Code ===============//
    }

}
