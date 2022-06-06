<?php

class WeightList {

    var $StrConn = "";
    public $_sts_no = "";
    public $_po_date = "";
    public $_c_no = "";
    public $_h_no = "";
    public $_weight = 0;
    public $_realweight = 0;
    public $_grade = "";
    public $_thick = 0;
    public $_thicks = 0;
    public $_width = 0;
    public $_widths = 0;
    public $_reference = "";
    public $_qa = "";
    public $_location = "";
    public $_i_date = "";
    public $_u_date = "";
    public $_pass = 0;
    public $_rows = "";
    public $_remark = "";
    public $_upload_status = "";
    public $_upload_grn_num = "";
    public $_upload_po = "";
    public $_upload_po_line = "";
    public $_sl_lot = "";
    public $_sl_tag_status = "";
    public $_sl_trans = "";
    public $_sl_tag_id = "";
    public $_s_no = "";

    function setConn($c) {
        $this->StrConn = $c;
    }

    private function CheckNumber($str) {
        if (!is_numeric($str)) {
            $str = 0;
        }
        $result = trim($str);
        $result = str_replace(" ", "", $result);
        $result = str_replace("  ", "", $result);
        $result = str_replace(",", ".", $result);
        $result = str_replace("..", ".", $result);
        $result = str_replace("O", "0", $result);
        $result = str_replace("o", "0", $result);
        return $result;
    }

    Public function InsertWithSP() {
        $cSql = new SqlSrv();
        $sql = "EXEC STS_WeightList_Insert  ";
        $sql = $sql . "  @grade = '" . $this->_grade . "' ";
        $sql = $sql . " ,@h_no ='" . $this->_h_no  . "' ";
        $sql = $sql . " ,@c_no ='" . $this->_c_no  . "' ";
        $sql = $sql . " ,@qa = '" . $this->_qa . "'  ";
        $sql = $sql . " ,@thick = " . $this->_thick . " ";
        $sql = $sql . " ,@width = " . $this->_width . " ";
        $sql = $sql . " ,@weight = " . $this->_weight . " "; 
        $rs = $cSql->SqlQuery($this->StrConn, $sql);
        $cSql = null;
        return $rs;
    }

    private function CheckText($str) {
        $result = trim($str);

        $result = str_replace("'", "", $result);
        return $result;
    }

    Function GetRowsWithCond($sWhere) {
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, "SELECT * FROM STS_WeightList  $sWhere ");
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function CheckDuplicate($sno) {
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, "SELECT sno FROM STS_WeightList WHERE sno='$sno' ");
        array_splice($rs, count($rs) - 1, 1);

        return count($rs);
    }

    Function ClearData($StartDate, $EndDate) {
        $cSql = new SqlSrv();
        $rs = $cSql->IsUpDel($this->StrConn, "DELETE FROM STS_WeightList WHERE po_date BETWEEN '$StartDate' AND '$EndDate'  ");
        return $rs;
    }

    Function Insert() {
        $sts_no = $this->CheckText($this->_sts_no);
        $po_date = $this->CheckText($this->_po_date);
        $c_no = $this->CheckText($this->_c_no);
        $h_no = $this->CheckText($this->_h_no);
        $weight = $this->CheckNumber($this->_weight);
        $realweight = $this->CheckNumber($this->_realweight);
        $grade = $this->CheckText($this->_grade);
        $thick = $this->CheckNumber($this->_thick);
        $thicks = $this->CheckNumber($this->_thicks);
        $width = $this->CheckNumber($this->_width);
        $widths = $this->CheckNumber($this->_widths);
        $reference = $this->CheckText($this->_reference);
        $qa = $this->CheckText($this->_qa);
        $location = $this->CheckText($this->_location);
        $i_date = $this->CheckText($this->_i_date);
        $u_date = $this->CheckText($this->_u_date);
        $s_no = $this->CheckText($this->_s_no);
        $pass = $this->CheckText($this->_pass);
        $rows = $this->CheckText($this->_rows);
        $remark = $this->CheckText($this->_remark);
        $upload_status = $this->CheckText($this->_upload_status);
        $upload_grn_num = $this->CheckText($this->_upload_grn_num);
        $upload_po = $this->CheckText($this->_upload_po);
        $upload_po_line = $this->_upload_po_line;
        $sl_lot = $this->_sl_lot;
        $sl_tag_status = $this->_sl_tag_status;
        $sl_trans = $this->_sl_trans;
        $sl_tag_id = $this->_sl_tag_id;


        $thick == "" ? $thick = 0 : $thick = $thick;
        $thicks == "" ? $thicks = 0 : $thicks = $thicks;
        $width == "" ? $width = 0 : $width = $width;
        $widths == "" ? $widths = 0 : $widths = $widths;
        $weight == "" ? $weight = 0 : $weight = $weight;
        $realweight == "" ? $realweight = 0 : $realweight = $realweight;
        $i_date == "0000-00-00" ? $i_date = "" : $i_date = $i_date;
        $u_date == "0000-00-00" ? $u_date = "" : $u_date = $u_date;
        $po_date == "0000-00-00" ? $po_date = "" : $i_date = $po_date;


        $cSql = new SqlSrv();
        $user_id = 0;
        if ($_SESSION["login_user_id"]) {
            $user_id = $_SESSION["login_user_id"];
        }

//        $cSql->IsUpDel($this->StrConn, "DELETE FROM STS_WeightList WHERE sts_no = '$sts_no'  ");

        $sql = "INSERT  INTO  STS_WeightList( "
                . "sts_no, po_date, i_date, u_date, "
                . "c_no, h_no, weight, real_weight, "
                . "grade, thick, real_thick, width, "
                . "real_width, reference, qa, location, "
                . "rows, sno, pass, remark, "
                . "upload_status, upload_grn_num, upload_po, upload_po_line, "
                . "sl_lot, sl_tag_status, sl_trans, sl_tag_id, "
                . "CreateBy, CreateDate"
                . ") VALUES  ("
                . "'$sts_no','$po_date','$i_date','$u_date',"
                . "'$c_no', '$h_no', $weight, $realweight,"
                . "'$grade', $thick, $thicks, $width, "
                . "$widths, '$reference', '$qa', '$location', "
                . "'$rows', '$s_no', $pass, '$remark', "
                . "'$upload_status', '$upload_grn_num', '$upload_po', $upload_po_line, "
                . "'$sl_lot', '$sl_tag_status', '$sl_trans', '$sl_tag_id', "
                . "$user_id, GETDATE()"
                . ")";
        $rs = $cSql->IsUpDel($this->StrConn, $sql);
        return $rs;
    }

}
