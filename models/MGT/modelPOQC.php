<?php

class PO_QC {

    var $StrConn = "";
    public $_sts_no = "";
    public $_sno = "";
    public $_reference = "";
    public $_qa = "";
    public $_udate = "";
    public $_user = "";
    public $_po_date = 0;
    public $_cno = "";
    public $_idate = "";
    public $_po_num = "";
    public $_po_line = "";
    public $_grn_num = "";
    public $_sl_lot = "";
    public $_sl_tag_id = "";
    public $_sl_tag_status = "";
    public $_sl_trans = "";
    public $sts_no = "";
    public $sno = "";
    public $reference = "";
    public $qa = "";
    public $udate = "";
    public $user = "";
    public $po_date = 0;
    public $cno = "";
    public $VarConn = array();
    public $idate = "";
    Private $MySql_Host = "";
    Private $MySql_User = "";
    Private $MySql_Db = "milltest";
    Private $MySql_Pass = "";
    private $QuerySelect = "SELECT * FROM po_qc  ";

    function setConn($var) {
        $this->MySql_Host = $var['mysql']['host'];
        $this->MySql_User = $var['mysql']['user'];
        $this->MySql_Pass = $var['mysql']['pass'];
        $this->MySql_Db = $var['mysql']['db'];
        $this->StrConn = $var;
    }

    function UpdateTransfer($sts_no) {
        $mysqli = new mysqli($this->MySql_Host, $this->MySql_User, $this->MySql_Pass, $this->MySql_Db);
        $TmpArray = array();
        $q = "UPDATE po_qc SET transfer = 1  WHERE sts_no  = '$sts_no' ";
        if ($result = $mysqli->query($q)) {
            // $result->free();
            $mysqli->close();
            return count($result);
        } else {
            return $this->GetError('in ' . __FILE__ . ' / ' . __FUNCTION__ . "()  " . $mysqli->error);
        }
    }

    function UpdateTransfer_Clear($Start, $End) {
        $mysqli = new mysqli($this->MySql_Host, $this->MySql_User, $this->MySql_Pass, $this->MySql_Db);
        $TmpArray = array();
        $q = "UPDATE po_qc SET transfer = NULL WHERE po_date BETWEEN '$Start' AND  '$End' ";
        if ($result = $mysqli->query($q)) {
            // $result->free();
            $mysqli->close();
            return count($result);
        } else {
            return $this->GetError('in ' . __FILE__ . ' / ' . __FUNCTION__ . "()  " . $mysqli->error);
        }
    }

    function UpdateTag() {
        $mysqli = new mysqli($this->MySql_Host, $this->MySql_User, $this->MySql_Pass, $this->MySql_Db);
        $TmpArray = array();
        $q = "UPDATE po_qc SET sl_tag_id = '" . $this->_sl_tag_id . "'  "
                . "WHERE sts_no = '" . $this->_sts_no . "' ";
        if ($result = $mysqli->query($q)) {
            // $result->free();
            $mysqli->close();
            return count($result);
        } else {
            return $this->GetError('in ' . __FILE__ . ' / ' . __FUNCTION__ . "()  " . $mysqli->error);
        }
    }

    function Uploaded() {
        $mysqli = new mysqli($this->MySql_Host, $this->MySql_User, $this->MySql_Pass, $this->MySql_Db);
        $TmpArray = array();
        $q = "UPDATE po_qc SET "
                . "upload_status = 'Y' "
                . ",upload_po =  '" . $this->_po_num . "'  "
                . ",upload_grn_num =  '" . $this->_grn_num . "' "
                . ",upload_po_line=  '" . $this->_po_line . "'  "
                . ",sl_lot = '" . $this->_sl_lot . "'  "
                . ",sl_tag_status = '0'  "
                . ",sl_trans= '" . $this->_sl_trans . "'  "
                . "WHERE sts_no = '" . $this->_sts_no . "' ";
        if ($result = $mysqli->query($q)) {
            // $result->free();
            $mysqli->close();
            return count($result);
        } else {
            return $this->GetError('in ' . __FILE__ . ' / ' . __FUNCTION__ . "()  " . $mysqli->error);
        }
    }

    function ClearGrn($sno) {
        $mysqli = new mysqli($this->MySql_Host, $this->MySql_User, $this->MySql_Pass, $this->MySql_Db);
        $TmpArray = array();
        $q = "UPDATE po_qc SET "
                . "upload_status = null "
                . ",upload_po =  null "
                . ",upload_grn_num =  null "
                . ",upload_po_line= null "
                . ",sl_lot = null  "
                . ",sl_tag_status = null  "
                . ",sl_trans=null , sl_tag_id = null "
                . "WHERE sno = '$sno' ";
        if ($result = $mysqli->query($q)) {
            // $result->free();
            $mysqli->close();
            return count($result);
        } else {
            return $this->GetError('in ' . __FILE__ . ' / ' . __FUNCTION__ . "()  " . $mysqli->error);
        }
    }

    function ClearTransfer($StartDate, $EndDate) {
        $mysqli = new mysqli($this->MySql_Host, $this->MySql_User, $this->MySql_Pass, $this->MySql_Db);
        $TmpArray = array();
        $q = "UPDATE po_qc SET transfer = 0 WHERE po_date BETWEEN '$StartDate' AND  '$EndDate' ";
        if ($result = $mysqli->query($q)) {
            // $result->free();
            $mysqli->close();
            return count($result);
        } else {
            return $this->GetError('in ' . __FILE__ . ' / ' . __FUNCTION__ . "()  " . $mysqli->error);
        }
    }

    function GetError($txt) {
        $err = "<div class='alert alert-danger'><strong>Error ! </strong>$txt</alert></div>";
        return $err;
    }

    function CountRows() {
        $mysqli = new mysqli($this->MySql_Host, $this->MySql_User, $this->MySql_Pass, $this->MySql_Db);
        $TmpArray = array();
        if ($result = $mysqli->query($this->QuerySelect . " ORDER BY sts_no ASC  LIMIT $limit ")) {
            $result->free();
            $mysqli->close();
            return count($result);
        } else {
            return $this->GetError('in ' . __FILE__ . ' / ' . __FUNCTION__ . "()  " . $mysqli->error);
        }
    }

    function GetConnectionStatus() {
        $mysqli = new mysqli($this->MySql_Host, $this->MySql_User, $this->MySql_Pass, $this->MySql_Db);
        if (!$mysqli) {
            return "<font color='red'>DISCONNECT &nbsp;<i class='fa fa-circle'></i></font>";
        } else {
            return "<font color='lightgreen'>CONNECTED &nbsp;<i class='fa fa-circle-o-notch fa-spin'></i></font>";
        }
    }

    function Ajax_GetRowsAll_Limit($limit) {
        $mysqli = new mysqli($this->MySql_Host, $this->MySql_User, $this->MySql_Pass, $this->MySql_Db);
        $TmpArray = array();
        if ($result = $mysqli->query($this->QuerySelect . " ORDER BY po_date DESC LIMIT $limit ")) {
            $i = 0;
            while ($row = $result->fetch_assoc()) {
                $i++;
                $TmpArray[$i] = $row;
            }
            $result->free();
            $mysqli->close();
            return $TmpArray;
        } else {
            return $this->GetError('in ' . __FILE__ . ' / ' . __FUNCTION__ . "()  " . $mysqli->error);
        }
    }

    function GetRowsWithCond2($Where) {
        $mysqli = new mysqli($this->MySql_Host, $this->MySql_User, $this->MySql_Pass, $this->MySql_Db);
        $TmpArray = array();

        if ($result = $mysqli->query($this->QuerySelect . $Where)) {
            $i = 0;
            while ($row = $result->fetch_assoc()) {
                $TmpArray[$i] = $row;
                $i++;
            }
            //$result->free();
            $mysqli->close();
            return $TmpArray;
        } else {
            return $this->GetError('in ' . __FILE__ . ' / ' . __FUNCTION__ . "()  " . $mysqli->error);
        }
    }

    function tblReportDetail($Where) {
        $mysqli = new mysqli($this->MySql_Host, $this->MySql_User, $this->MySql_Pass, $this->MySql_Db);
        $TmpArray = array();

        if ($result = $mysqli->query('select * FROM product_test ' . $Where)) {
            $i = 0;
            while ($row = $result->fetch_assoc()) {
                $TmpArray[$i] = $row;
                $i++;
            }
            //$result->free();
            $mysqli->close();
            return $TmpArray;
        } else {
            return $this->GetError('in ' . __FILE__ . ' / ' . __FUNCTION__ . "()  " . $mysqli->error);
        }
    }

    function GetRowsWithCond($Where) {
        $mysqli = new mysqli($this->MySql_Host, $this->MySql_User, $this->MySql_Pass, $this->MySql_Db);
        $TmpArray = array();
        if ($result = $mysqli->query($this->QuerySelect . $Where)) {
            $i = 0;
            while ($row = $result->fetch_assoc()) {
                $i++;
                $TmpArray[$i] = $row;
            }
            $result->free();
            $mysqli->close();
            return $TmpArray;
        } else {
            return $this->GetError('in ' . __FILE__ . ' / ' . __FUNCTION__ . "()  " . $mysqli->error);
        }
    }

    function Ajax_GetRowsWithDate($startDate, $endDate, $st, $flt, $filterStsNo, $searchType) {
        $mysqli = new mysqli($this->MySql_Host, $this->MySql_User, $this->MySql_Pass, $this->MySql_Db);
        $TmpArray = array();
        $QsT = "";
        $QFlt = " AND reference LIKE '%$flt%' ";
        $qFlt2 = " AND sno LIKE '%$filterStsNo%' ";
        $fltType = "";

        if ($st == "A") {
            $QsT = "";
        } else if ($st == "Y") {
            $QsT = " AND upload_status ='Y' ";
        } else if ($st == "N") {
            $QsT = " AND ( upload_status ='' OR upload_status ='N' OR  upload_status is  null ) ";
        }
        $searchType == "normal" ? $fltType = "" : $fltType = " AND sl_tag_id is not  null ";
        if ($result = $mysqli->query($this->QuerySelect . " WHERE po_date BETWEEN  '$startDate' AND '$endDate'  $QsT   $QFlt $qFlt2 $fltType ORDER BY sts_no DESC ")) {
            $i = 0;
            while ($row = $result->fetch_assoc()) {
                $TmpArray[$i] = $row;
                $i++;
            }
            $result->free();
            $mysqli->close();
            return $TmpArray;
        } else {
            return $this->GetError('in ' . __FILE__ . ' / ' . __FUNCTION__ . "()  " . $mysqli->error);
        }
    }

    function GetRowsOnce() {
        $mysqli = new mysqli($this->MySql_Host, $this->MySql_User, $this->MySql_Pass, $this->MySql_Db);
        $TmpArray = array();
        if ($result = $mysqli->query($this->QuerySelect . " ORDER BY po_date DESC LIMIT 1 ")) {
            $i = 0;
            while ($row = $result->fetch_assoc()) {
                $i++;
                $TmpArray[$i] = $row;
            }
            $result->free();
            $mysqli->close();
            return $TmpArray;
        } else {
            return $this->GetError('in ' . __FILE__ . ' / ' . __FUNCTION__ . "()  " . $mysqli->error);
        }
    }

    function SearchLine($sno) {
        $mysqli = new mysqli($this->MySql_Host, $this->MySql_User, $this->MySql_Pass, $this->MySql_Db);
        $TmpArray = array();
        if ($result = $mysqli->query($this->QuerySelect . " WHERE sno = '$sno' ")) {
            $i = 0;
            while ($row = $result->fetch_assoc()) {
                $i++;
                $TmpArray[$i] = $row;
            }
            $result->free();
            $mysqli->close();
            return $TmpArray;
        } else {
            return $this->GetError('in ' . __FILE__ . ' / ' . __FUNCTION__ . "()  " . $mysqli->error);
        }
    }

    function SearchRealWeight($sno) {
        $mysqli = new mysqli($this->MySql_Host, $this->MySql_User, $this->MySql_Pass, $this->MySql_Db);
        $tmpResult = "";

        if ($result = $mysqli->query($this->QuerySelect . " WHERE sno = '$sno' ")) {
            $row = mysqli_fetch_assoc($result);
            $tmpResult = $row["realweight"];
//            $i = 0;
//            while ($row = $result->fetch_assoc()) {
//                $i++;
//                $TmpArray[$i] = $row;
//            }
            $result->free();
            $mysqli->close();
            return $tmpResult;
        } else {
            return $this->GetError('in ' . __FILE__ . ' / ' . __FUNCTION__ . "()  " . $mysqli->error);
        }
    }

    function UpdateCheckHeatNoStatus($sts_no, $h_no, $thick, $width, $CurrAction) {
        $mysqli = new mysqli($this->MySql_Host, $this->MySql_User, $this->MySql_Pass, $this->MySql_Db);
        $TmpArray = array();
        if ($CurrAction == 1) {
            $q = "UPDATE po_qc SET check_heat_no = 0  WHERE h_no  = '$h_no' and thick ='$thick' and width ='$width' ";
            $q2 = "UPDATE po_qc SET check_coil_no = 0  WHERE h_no  = '$h_no' and thick ='$thick' and width ='$width' ";
        } else {
            $q = "UPDATE po_qc SET check_heat_no = 1  WHERE h_no  = '$h_no' and thick ='$thick' and width ='$width' ";
            $q2 = "UPDATE po_qc SET check_coil_no = 1  WHERE h_no  = '$h_no' and thick ='$thick' and width ='$width' and sts_no ='$sts_no' ";
        }
        $mysqli->query($q);
        $mysqli->query($q2);

        if ($result = $mysqli->query("SELECT check_heat_no FROM po_qc WHERE sts_no  = '$sts_no' ")) {
            $mysqli->query($q);

            $i = 0;
            while ($row = $result->fetch_assoc()) {
                $TmpArray[$i] = $row;
                $i++;
            }
            $result->free();
            $mysqli->close();
            return $TmpArray;
        } else {
            return $this->GetError('in ' . __FILE__ . ' / ' . __FUNCTION__ . "()  " . $mysqli->error);
        }
    }

    function insert_product_test($sno) {
        $mysqli = new mysqli($this->MySql_Host, $this->MySql_User, $this->MySql_Pass, $this->MySql_Db);
        $TmpArray = array();
        $q = "INSERT INTO product_test (sno, size, stardard) VALUES ('$sno', '', '') ";
        if (1 == 1) {
            $mysqli->query($q);
            return $sno;
        } else {
            return $this->GetError('in ' . __FILE__ . ' / ' . __FUNCTION__ . "()  " . $mysqli->error);
        }
    }

    function update_product_test($id, $col_name, $valdata) {
        $mysqli = new mysqli($this->MySql_Host, $this->MySql_User, $this->MySql_Pass, $this->MySql_Db);
        $TmpArray = array();
        $q = "UPDATE product_test SET $col_name = '$valdata'  WHERE id ='$id' ";
        $mysqli->query($q);
    }

    function delete_product_test($id, $sno) {
        $mysqli = new mysqli($this->MySql_Host, $this->MySql_User, $this->MySql_Pass, $this->MySql_Db);
        $TmpArray = array();
        $q = "DELETE FROM product_test WHERE id ='$id' ";
        $mysqli->query($q);
        return $sno;
    }

    function UpdateQcTestLab($sno, $col_name, $valdata) {
        $mysqli = new mysqli($this->MySql_Host, $this->MySql_User, $this->MySql_Pass, $this->MySql_Db);
        $TmpArray = array();
        //$q = "UPDATE po_qc SET $col_name = '$valdata'  WHERE sno ='$sno' ";
        $q = "update po_qc  set $col_name = '$valdata' where h_no in (select * FROM (select h_no from po_qc where sno ='$sno') as po_qc_tmp) "
                . " and  width in (select * FROM (select width from po_qc where sno ='$sno') as po_qc_tmp2)  ";
        $mysqli->query($q);
    }

    function GetRowsWithCond23($sno) {
        $mysqli = new mysqli($this->MySql_Host, $this->MySql_User, $this->MySql_Pass, $this->MySql_Db);
        $TmpArray = array();

        if ($result = $mysqli->query("select * FROM po_qc where sno = $sno")) {
            $i = 0;
            while ($row = $result->fetch_assoc()) {
                $TmpArray[$i] = $row;
                $i++;
            }
            //$result->free();
            $mysqli->close();
            return $TmpArray;
        } else {
            return $this->GetError('in ' . __FILE__ . ' / ' . __FUNCTION__ . "()  " . $mysqli->error);
        }
    }
    
//Check HOT ROLL Do formimng    
//    SELECT distinct STS_Check_heat_no.sts_no
//	, flagFM = (case when flag.sts_no is null then 'ทำ Forming แล้ว' else 'ยังไม่ได้ทำ' end)
//		
//FROM STS_Check_heat_no left join 
//
//(
//SELECT DISTINCT a.sts_no
//FROM     STS_Check_heat_no a
//except
//                      (SELECT distinct Mv_Bc_Tag.sts_no
//                       FROM      Mv_Bc_Tag 
//                       WHERE   (Mv_Bc_Tag.lot LIKE 'FM%'))) flag
//		on STS_Check_heat_no.sts_no = flag.sts_no
//
//order by (case when flag.sts_no is null then 'ทำ Forming แล้ว' else 'ยังไม่ได้ทำ' end)

}
