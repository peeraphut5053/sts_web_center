<?php

//=========== Global Scope Variable ===========//
$host = $_SERVER['HTTP_HOST'];
$root = $_SERVER['DOCUMENT_ROOT'];
$self = $_SERVER['PHP_SELF'];


$realpath= realpath(dirname(__FILE__) . '/../..') ;
include($realpath . "/include/config.php");
include($realpath . "/include/sqlConn.php");

//===========================================//
class Lot {

//========= Inside Class Variable ===========//
    var $StrConn = "";
    public $_lot = "";
    public $_item = "";
    public $_rcvd_qty = "";
    public $_create_date = "";
    public $_exp_date = "";
    public $_purge_date = "";
    public $_vend_lot = "";
    public $site_default = "STS";

    function setConn($c) {

        $this->StrConn = $c;
    }

//============================================//
//========= Initial Class Utilities ===========//
    function GetDataArray_SL($sql) {
        $cSql = new SqlSrv();
        $GlobConn = $GLOBALS["ConnSL"];
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

    Function UpdateLotViaPrintTag($lot, $sts_no, $coil_no, $item, $realweights, $realwidths, $realthicks) {

        $cSql = new SqlSrv();
        $GlobConn = $GLOBALS["conn_sl"];
        $sql = "UPDATE lot_mst SET Uf_coil_no ='$coil_no'  , Uf_act_weight=$realweights ,Uf_act_width=$realwidths , uf_act_tickness = $realthicks ,sts_no = '$sts_no'  WHERE lot = '$lot' AND item = '$item' ";
        $rs = $cSql->IsUpDel($GlobConn, $sql);

        return $rs;
    }

    Function UpdateSts_CoilNo($lot, $sts_no, $coil_no, $item) {
        $cSql = new SqlSrv();
        $GlobConn = $GLOBALS["conn_sl"];
        $sql = "UPDATE lot_mst SET Uf_coil_no ='$coil_no' ,sts_no = '$sts_no'  WHERE lot = '$lot'  AND item = '$item'  ";
        $rs = $cSql->IsUpDel($GlobConn, $sql);
        return $rs;
    }

    Function UpdateActWeight($lot, $act_weight) {
        $cSql = new SqlSrv();
        $GlobConn = $GLOBALS["ConnSL"];
        $sql = "UPDATE lot_mst SET Uf_act_weight ='$act_weight'  WHERE lot = '$lot' ";
        $rs = $cSql->IsUpDel($GlobConn, $sql);
        return $rs;
    }

    function CheckLotDuplicate($lot) {
        $SqlConnectionInfo = $GLOBALS["var"]["mysql"];
        $sql = "SELECT lot FROM  lot_mst  where lot = '$lot' ";
        $rs = $this->GetDataArray_SL($sql);
        $RunningDigit = 9;
        $result = "";
        if (count($rs) >= 1) {
            $tmpDocNo = trim($rs[0]["lot"]);
            $tmpDocNoDate = substr($tmpDocNo, 0, 6);
            $tmpDocNoCutDate = substr($tmpDocNo, 6, 13);
            $val = str_pad(strval($tmpDocNoCutDate) + 1, $RunningDigit, '0', STR_PAD_LEFT);
            $result = $tmpDocNoDate . $val;
        } else {
            $result = $lot;
        }

        return $result;
    }

    function FindBlankActWeight($start_date, $end_date) {
        $sql = "SELECT lot,rcvd_qty , sts_no ,Uf_act_weight FROM  lot_mst   WHERE ( create_date BETWEEN cast(CONVERT(varchar, '$start_date', 120) as datetime)  AND  cast(CONVERT(varchar, '$end_date', 120) as datetime)  ) AND ( sts_no <> ''  or sts_no <> Null ) ";
        $rs = $this->GetDataArray_SL($sql);
        $result = "";
        $row_template = "";

        return $rs;
    }

    function GenNewLotNum() {

        $SqlConnectionInfo = $GLOBALS["var"]["mysql"];

        $RunningDigit = 9;
        $CurrDate = date("ymd");
        $RunningInit = "000000001";
        $sql = "SELECT TOP 1 lot FROM  lot_mst   ORDER BY recorddate  DESC , lot DESC  ";
        $rs = $this->GetDataArray_SL($sql);
        $tmpDocNo = "";
        $tmpDocNoCutPrefix = "";
        $tmpDocNoDate = "";
        $tmpDocNoCutDate = "";
        $val = "";

        if (count($rs) < 1) {
            return $CurrDate . $RunningInit;
        } else {
            $tmpDocNo = trim($rs[0]["lot"]);
            $tmpDocNoDate = substr($tmpDocNo, 0, 6);
            $tmpDocNoCutDate = substr($tmpDocNo, 6, 13);

            if ($CurrDate == $tmpDocNoDate) {
                $val = str_pad(strval($tmpDocNoCutDate) + 1, $RunningDigit, '0', STR_PAD_LEFT);
                return $CurrDate . "" . $val;
            } else {
                return $CurrDate . $RunningInit;
            }
//            if (strlen($tmpDocNo) < 15) {
//
//
////                $tmpReturnVal = $CurrDate . $RunningInit;
////                $sqlCheck = "SELECT TOP 1 lot FROM  lot_mst  WHERE lot = '$tmpReturnVal' ";
////                $rsCheck = $this->GetDataArray_SL($sqlCheck);
//
////                if (count($rsCheck) < 1) {
////                    return $CurrDate . $RunningInit;
////                } else {
////                    $sqlCheck2 = "select distinct top 1 lot from lot_mst where lot like '$CurrDate%' order by lot desc  ";
////                    $rsCheck2 = $this->GetDataArray_SL($sqlCheck2);
////                    if ($rsCheck2[0]["lot"]) {
////                        $tmpDocNoCutDateCheck = substr($rsCheck2[0]["lot"], 6, 13);
////                        $IncreTempId = strval($tmpDocNoCutDateCheck) + 1;
////                        $valCheck = str_pad($IncreTempId, $RunningDigit, '0', STR_PAD_LEFT);
////                        return $CurrDate . "" . $valCheck;
////                    } else {
////                        return $CurrDate . $RunningInit;
////                    }
////                }
//            } else {
//                $tmpDocNoDate = substr($tmpDocNo, 0, 6);
//                $tmpDocNoCutDate = substr($tmpDocNo, 6, 13);
//                if ($CurrDate == $tmpDocNoDate) {
//                    $val = str_pad(strval($tmpDocNoCutDate) + 1, $RunningDigit, '0', STR_PAD_LEFT);
//                    return $CurrDate . "" . $val;
//                } else {
//                    return $CurrDate . $RunningInit;
//                }
//            }
        }
//================Gen Head Code ===============//
    }

}
