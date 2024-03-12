<?php

class Invoice {

    var $StrConn = "";
    public $_StartDate = "";
    public $_EndDate = "";
    public $_Customers = array();
    public $_item = "";
    public $_inv_num = "";
    public $_co_line = "";
    public $_size = "";
    public $_thick = "";
    public $_width = "";
    public $_start_invdate = "";
    public $_end_invdate = "";
    public $_product_code = "";
    public $_item_group = "";
    public $_edit_um = "";
    public $_edit_qty = "";
    public $_edit_price = "";
    public $_edit_disc = "";
    public $_start_inv = "";
    public $_end_inv = "";
    public $_Year = "";
    public $_Month = "";
    public $_keyword = "";
    public $_txtFromDate_start = "";
    public $_txtFromDate_end = "";
    public $_txtFromCoNum_start = "";
    public $_txtFromCoNum_end = "";
    public $_cus_type = "";
    public $_txtFromCusnumOrName = "";
    public $_txtFromItemOrDes = "";
    public $cust_num = "";
    public $cust_seq = "";
    public $_txtCity = "";
    public $_txtcustomerpo = "";
    public $_txtFromDateGroup = "";
    public $_txtToDateGroup = "";

    function setConn($c) {
        $this->StrConn = $c;
    }

    function GetCNByHdr() {
        $inv_num = $this->_inv_num;
        $query = "SELECT  * FROM V_WebApp_InvItem_IN   WHERE  inv_num LIKE 'CN%' AND orig_inv_num = '$inv_num'  ";
        $query .= " ORDER BY co_line  asc";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetInvTest() {
        $keyword = $this->_keyword;
        $txtFromDate_start = $this->_txtFromDate_start;
        $txtFromDate_end = $this->_txtFromDate_end;

        $query = "SELECT CONVERT(varchar,inv_date,103) as inv_date_conv ,* FROM V_WebApp_InvItem_EX where (inv_date BETWEEN '$txtFromDate_start 00:00:00' AND '$txtFromDate_end 23:59:59')   "
                . " or (co_num BETWEEN '$txtFromCoNum_start' AND '$txtFromCoNum_end') ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetRPTINV_Outstanding() {
        $cus_type = $this->_cus_type;
        $keyword = $this->_keyword;
        $txtFromDate_start = $this->_txtFromDate_start;
        $txtFromDate_end = $this->_txtFromDate_end;
        $txtFromCoNum_start = $this->_txtFromCoNum_start;
        $txtFromCoNum_end = $this->_txtFromCoNum_end;
        $txtFromCusnumOrName = $this->_txtFromCusnumOrName;
        $txtFromItemOrDes = $this->_txtFromItemOrDes;
        if (($txtFromDate_start) && ($txtFromDate_end)) {
            $order_date = " AND ( order_date BETWEEN '$txtFromDate_start 00:00:00' AND '$txtFromDate_end 23:59:59' ) ";
        }
        $query = "SELECT CONVERT(varchar,order_date,103) as order_date_conv ,datediff(day, order_date,getdate() ) as dateDUE,* FROM V_WebApp_InvItem_OutStanding "
                . "WHERE "
                . "LEFT(cust_num,2) = '$cus_type' ";


        if ($this->_txtFromDate_start != "") {
            $query = $query . "AND ( order_date BETWEEN '$txtFromDate_start 00:00:00' AND '$txtFromDate_end 23:59:59' ) ";
        }
        if ($txtFromCoNum_start != "") {
            $query = $query . "AND ( co_num BETWEEN '$txtFromCoNum_start' AND '$txtFromCoNum_end' ) ";
        }
        if ($txtFromCusnumOrName != "") {
            $query = $query . "AND ( name LIKE '%" . $txtFromCusnumOrName . "%' or cust_num LIKE '%" . $txtFromCusnumOrName . "%' ) ";
        }
        if ($txtFromItemOrDes != "") {
            $query = $query . "AND ( item LIKE '%" . $txtFromItemOrDes . "%' or description LIKE '%" . $txtFromItemOrDes . "%' ) ";
        }

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetRPTINV_OutstandingEx() {
        $cus_type = $this->_cus_type;
        $keyword = $this->_keyword;
        $txtFromDate_start = $this->_txtFromDate_start;
        $txtFromDate_end = $this->_txtFromDate_end;
        $txtFromCoNum_start = $this->_txtFromCoNum_start;
        $txtFromCoNum_end = $this->_txtFromCoNum_end;
        $txtFromCusnumOrName = $this->_txtFromCusnumOrName;
        $txtFromItemOrDes = $this->_txtFromItemOrDes;
        $txtCity = $this->_txtCity;
        $txtcustomerpo = $this->_txtcustomerpo;

        if (($txtFromDate_start) && ($txtFromDate_end)) {
            $order_date = " AND ( order_date BETWEEN '$txtFromDate_start 00:00:00' AND '$txtFromDate_end 23:59:59' ) ";
        }
        $query = "SELECT "
                . "distinct "
                . "CONVERT(varchar,order_date,120) as order_date_conv ,datediff(day, getdate(),due_date ) as dateDUE,"
                . "CONVERT(varchar,due_date,120) as due_date_conv ,"
                . "V_WebApp_InvItem_OutStanding_2.* FROM V_WebApp_InvItem_OutStanding_2 "
                . "WHERE ( LEFT(co_num,2) = 'EX' or LEFT(co_num,2) ='EB') and stat <> 'C' ";
        



        if ($this->_txtFromDate_start != "") {
            $query = $query . "AND ( order_date BETWEEN '$txtFromDate_start 00:00:00' AND '$txtFromDate_end 23:59:59' ) ";
        }
        if ($txtFromCoNum_start != "") {
            $query = $query . "AND ( co_num BETWEEN '$txtFromCoNum_start' AND '$txtFromCoNum_end' ) ";
        }
        if ($txtFromCusnumOrName != "") {
            $query = $query . "AND ( V_WebApp_InvItem_OutStanding_2.name LIKE '%" . $txtFromCusnumOrName . "%' or V_WebApp_InvItem_OutStanding_2.cust_num LIKE '%" . $txtFromCusnumOrName . "%' ) ";
        }
        if ($txtFromItemOrDes != "") {
            $query = $query . "AND ( V_WebApp_InvItem_OutStanding_2.item LIKE '%" . $txtFromItemOrDes . "%' or V_WebApp_InvItem_OutStanding_2.description LIKE '%" . $txtFromItemOrDes . "%' ) ";
        }
        if ($txtCity != "") {
            $query = $query . "AND ( V_WebApp_InvItem_OutStanding_2.city LIKE '%" . $txtCity . "%'  ) ";
        }
        if ($txtcustomerpo != "") {
            $query = $query . "AND ( cust_po LIKE '%" . $txtcustomerpo . "%'  ) ";
        }

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetJobDetailModal($ord_num,$ord_line) {
        
        $query = "select LEFT(CONVERT(varchar, job_date, 120), 10) AS job_date"
                . ",ord_num"
                . ",ord_line"
                . ",item"
                . ",qty_released"
                . ",qty_complete"
                . ",qty_scrapped"
                . " FROM job_mst where ord_num = '$ord_num' and ord_line = '$ord_line' ";
        $Customers = $this->_Customers;
        $Criteria = "";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetInvHdr() {
        $start_invdate = $this->_start_invdate;
        $end_invdate = $this->_end_invdate;
        $query = "SELECT  * FROM inv_hdr_mst "
                . "WHERE (inv_num LIKE 'IN%') AND ( inv_date BETWEEN '$start_invdate' AND '$end_invdate' ) ";
        $Customers = $this->_Customers;
        $Criteria = "";


        if (isset($Customers[0])) {
            $Criteria .= " AND ( ";
            foreach ($Customers as $ii => $rr) {
                $Criteria .= " cust_num = '$rr' OR ";
            }
            $query .= substr($Criteria, 0, -3) . " ) ";
        }
//        return $query;
        $query = $query . " ORDER BY inv_date asc";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetInvItem2() {
        $start_invdate = $this->_start_invdate;
        $end_invdate = $this->_end_invdate;
        $item_group = $this->_item_group;

        $query = "SELECT  distinct  * "
                . "FROM V_WebApp_InvItem_IN "
                . "WHERE 1=1 "
                . "AND(item_code <> '' ) AND ( inv_date BETWEEN '$start_invdate' AND '$end_invdate' ) "
                . "AND (item_group LIKE '$item_group%')  "
                . "ORDER BY inv_date asc ";

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function CheckInv() {
        $inv_num = $this->_inv_num;
        $query = "SELECT * FROM inv_item_mst WHERE inv_num ='$inv_num' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetInvItem_deposit() {
        $start_invdate = $this->_start_invdate;
        $end_invdate = $this->_end_invdate;
        $start_inv = $this->_start_inv;
        $end_inv = $this->_end_inv;
        $item = $this->_item;
        $query = "SELECT  * FROM V_WebApp_InvDeposit_detail "
                . "WHERE ( Uf_Ref_Invnum <> '' OR Uf_Ref_Invnum <> null )  "
                . " AND (inv_num like 'IN%' OR inv_num like 'CN%' OR inv_num like 'DN%'  ) and amt>0  ";
        $criteria_inv = "";
        if (($start_inv) && ($end_inv)) {
            $criteria_inv = " AND ( inv_num between '$start_inv' AND '$end_inv' ) ";
        }
        $criteria_date = " ";
        if (($start_invdate) && ($end_invdate)) {
            $criteria_date = " AND ( inv_date BETWEEN '$start_invdate' AND '$end_invdate' ) ";
        }
        $query = $query . $criteria_inv . $criteria_date;
        if ($this->_item != "") {
            $query = $query . "AND ( CONCAT(item , ' ' , description)  LIKE '%" . trim($this->_item) . "%' ) ";
        }
        if ($this->_size != "") {
            $query = $query . "AND ( item_size LIKE '%" . trim($this->_size) . "%' ) ";
        }
        if ($this->_item != "") {
            $query = $query . "AND ( item LIKE '%" . trim($this->_item) . "%' ) ";
        }
        if ($this->_thick != "") {
            if (strpos($this->_thick, '-') !== false) {
                $thickExplode = explode("-", $this->_thick);
                $query = $query . "AND ( CAST(item_thick_conv as decimal(15,5))  BETWEEN   " . $thickExplode[0] . " AND  " . $thickExplode[1] . " ) ";
            } else {
                $query = $query . "AND ( CAST(item_thick_conv as decimal(15,5))  =   " . $this->_thick . "  ) ";
            }
        }
        if ($this->_width != "") {
            if (strpos($this->_width, '-') !== false) {
                $widthExplode = explode("-", $this->_width);
                $query = $query . "AND ( CAST(item_width_conv as decimal(15,5)) BETWEEN   " . $widthExplode[0] . " AND  " . $widthExplode[1] . " ) ";
            } else {
                $query = $query . "AND ( CAST(item_width_conv as decimal(15,5))  BETWEEN 0 AND  " . trim($this->_width) . "  ) ";
            }
        }
        $Customers = "";
        $Customers = $this->_Customers;
        $Criteria = "";
        if (isset($Customers[0])) {
            $Criteria .= " AND ( ";
            foreach ($Customers as $ii => $rr) {
                $Criteria .= " cust_num = '$rr' OR ";
            }
            $query .= substr($Criteria, 0, -3) . " ) ";
        }
        $query = $query . " ORDER BY inv_date , inv_num  asc";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetInvItem_2($SwitchSale) {
        $start_invdate = $this->_start_invdate;
        $end_invdate = $this->_end_invdate;

        $start_inv = $this->_start_inv;
        $end_inv = $this->_end_inv;

        $item = $this->_item;
        $SaleCriteria = "";

        if ($SwitchSale == "IN") {
            $SaleCriteria = " AND (inv_num like 'IN%' OR inv_num like 'CN%' OR inv_num like 'DN%'  ) ";
        } else if ($SwitchSale == "EX") {
            $SaleCriteria = " AND (inv_num like 'EX%' OR inv_num like 'EB%'  OR inv_num like 'CN%' OR inv_num like 'DN%'  ) ";
        }
        $criteria_inv = "";
        if (($start_inv) && ($end_inv)) {
            $criteria_inv = " AND ( inv_num between '$start_inv' AND '$end_inv' ) ";
        }
        $criteria_date = " ";
        if (($start_invdate) && ($end_invdate)) {
            $criteria_date = " AND ( inv_date BETWEEN '$start_invdate' AND '$end_invdate' ) ";
        }
        $query = "SELECT  * FROM V_WebApp_InvItem_All "
                . "WHERE  has_inv_child <=0 and item <> 'ZR-22400'   and Uf_Ref_InvNum is  null     "
                . " $criteria_inv "
                . " $SaleCriteria "
                . " $criteria_date ";
        if ($this->_item != "") {
            $query = $query . "AND ( CONCAT(item , ' ' , description)  LIKE '%" . trim($this->_item) . "%' ) ";
        }
        if ($this->_size != "") {
            $query = $query . "AND ( item_size LIKE '%" . trim($this->_size) . "%' ) ";
        }
        if ($this->_item != "") {
            $query = $query . "AND ( item LIKE '%" . trim($this->_item) . "%' ) ";
        }
        if ($this->_thick != "") {
            if (strpos($this->_thick, '-') !== false) {
                $thickExplode = explode("-", $this->_thick);
                $query = $query . "AND ( CAST(item_thick_conv as decimal(15,5))  BETWEEN   " . $thickExplode[0] . " AND  " . $thickExplode[1] . " ) ";
            } else {
                $query = $query . "AND ( CAST(item_thick_conv as decimal(15,5))  =   " . $this->_thick . "  ) ";
            }
        }
        if ($this->_width != "") {
            if (strpos($this->_width, '-') !== false) {
                $widthExplode = explode("-", $this->_width);
                $query = $query . "AND ( CAST(item_width_conv as decimal(15,5)) BETWEEN   " . $widthExplode[0] . " AND  " . $widthExplode[1] . " ) ";
            } else {

                $query = $query . "AND ( CAST(item_width_conv as decimal(15,5))  BETWEEN 0 AND  " . trim($this->_width) . "  ) ";
            }
        }

        $Customers = $this->_Customers;
        $Criteria = "";
        if (isset($Customers[0])) {
            $Criteria .= " AND ( ";
            foreach ($Customers as $ii => $rr) {
                $Criteria .= " cust_num = '$rr' OR ";
            }
            $query .= substr($Criteria, 0, -3) . " ) ";
        }
        // return $query;
        $query = $query . " ORDER BY inv_date , inv_num  asc";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetInvItem_EX() {
        $start_invdate = $this->_start_invdate;
        $end_invdate = $this->_end_invdate;

        $start_inv = $this->_start_inv;
        $end_inv = $this->_end_inv;

        $item = $this->_item;

        $criteria_inv = "";
        if (($start_inv) && ($end_inv)) {
            $criteria_inv = " AND ( inv_num between '$start_inv' AND '$end_inv' ) ";
        }
        $criteria_date = " ";
        if (($start_invdate) && ($end_invdate)) {
            $criteria_date = " AND ( inv_date BETWEEN '$start_invdate 00:00:00' AND '$end_invdate 23:59:59' ) ";
        }
        $query = "SELECT CONVERT(varchar,inv_date,103) as inv_date_conv , * FROM V_WebApp_InvItem_EX "
                . "WHERE   1=1   "
                . " $criteria_inv "
                . " $criteria_date ";
        if ($this->_item != "") {
            $query = $query . "AND ( CONCAT(item , ' ' , description)  LIKE '%" . trim($this->_item) . "%' ) ";
        }
        if ($this->_size != "") {
            $query = $query . "AND ( item_size LIKE '%" . trim($this->_size) . "%' ) ";
        }
        if ($this->_item != "") {
            $query = $query . "AND ( item LIKE '%" . trim($this->_item) . "%' ) ";
        }
        if ($this->_thick != "") {
            if (strpos($this->_thick, '-') !== false) {
                $thickExplode = explode("-", $this->_thick);
                $query = $query . "AND ( CAST(item_thick_conv as decimal(15,5))  BETWEEN   " . $thickExplode[0] . " AND  " . $thickExplode[1] . " ) ";
            } else {
                $query = $query . "AND ( CAST(item_thick_conv as decimal(15,5))  =   " . $this->_thick . "  ) ";
            }
        }
        if ($this->_width != "") {
            if (strpos($this->_width, '-') !== false) {
                $widthExplode = explode("-", $this->_width);
                $query = $query . "AND ( CAST(item_width_conv as decimal(15,5)) BETWEEN   " . $widthExplode[0] . " AND  " . $widthExplode[1] . " ) ";
            } else {

                $query = $query . "AND ( CAST(item_width_conv as decimal(15,5))  BETWEEN 0 AND  " . trim($this->_width) . "  ) ";
            }
        }

        $Customers = $this->_Customers;
        $Criteria = "";
        if (isset($Customers[0])) {
            $Criteria .= " AND ( ";
            foreach ($Customers as $ii => $rr) {
                $Criteria .= " cust_num = '$rr' OR ";
            }
            $query .= substr($Criteria, 0, -3) . " ) ";
        }
        // return $query;
        $query = $query . " ORDER BY inv_date , inv_num  asc";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetInvItem_IN() {
        $start_invdate = $this->_start_invdate;
        $end_invdate = $this->_end_invdate;

        $start_inv = $this->_start_inv;
        $end_inv = $this->_end_inv;

        $item = $this->_item;

        $query = "SELECT  CONVERT(varchar,inv_date,103) as inv_date_conv ,FORMAT(AMT, 'N2') as AMT_2d ,FORMAT(VAT, 'N2') as VAT_2d,FORMAT(AMT_TOTAL, 'N2') as AMT_TOTAL_2d, *  FROM V_WebApp_InvItem_IN 
                WHERE acct <> '22400'";
        if (($start_inv) && ($end_inv)) {
            $query .= " AND ( inv_num between '$start_inv' AND '$end_inv' ) ";
        }
        if (($start_invdate) && ($end_invdate)) {
            $query .= " AND ( inv_date BETWEEN '$start_invdate 00:00:00' AND '$end_invdate 23:59:59' ) ";
        }
        if ($this->_item != "") {
            $query = $query . "AND ( CONCAT(item , ' ' , description)  LIKE '%" . trim($this->_item) . "%' ) ";
        }
        if ($this->_size != "") {
            $query = $query . "AND ( item_size LIKE '%" . trim($this->_size) . "%' ) ";
        }
        if ($this->_item != "") {
            $query = $query . "AND ( item LIKE '%" . trim($this->_item) . "%' ) ";
        }
        if ($this->_thick != "") {
            if (strpos($this->_thick, '-') !== false) {
                $thickExplode = explode("-", $this->_thick);
                $query = $query . "AND ( CAST(item_thick_conv as decimal(15,5))  BETWEEN   " . $thickExplode[0] . " AND  " . $thickExplode[1] . " ) ";
            } else {
                $query = $query . "AND ( CAST(item_thick_conv as decimal(15,5))  =   " . $this->_thick . "  ) ";
            }
        }
        if ($this->_width != "") {
            if (strpos($this->_width, '-') !== false) {
                $widthExplode = explode("-", $this->_width);
                $query = $query . "AND ( CAST(item_width_conv as decimal(15,5)) BETWEEN   " . $widthExplode[0] . " AND  " . $widthExplode[1] . " ) ";
            } else {
                $query = $query . "AND ( CAST(item_width_conv as decimal(15,5))  BETWEEN 0 AND  " . trim($this->_width) . "  ) ";
            }
        }

        $Customers = $this->_Customers;
        $Criteria = "";
        if (isset($Customers[0])) {
            $Criteria .= " AND ( ";
            foreach ($Customers as $ii => $rr) {
                $Criteria .= " cust_num = '$rr' OR ";
            }
            $query .= substr($Criteria, 0, -3) . " ) ";
        }

        $query = $query . " ORDER BY inv_date , inv_num , co_num, co_line , Uf_DoHdr_car_num, item";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetInvItem_IN2021() {
        $start_invdate = $this->_start_invdate;
        $end_invdate = $this->_end_invdate;

        $start_inv = $this->_start_inv;
        $end_inv = $this->_end_inv;

        $item = $this->_item;

        $query = "SELECT  CONVERT(varchar,inv_date,103) as inv_date_conv ,FORMAT(AMT, 'N2') as AMT_2d, * 
        ,recpt_date =   isnull(stuff(
                                (SELECT ',' + convert(varchar,convert(date,(recpt_date)))
                              FROM artran_mst arsub
                                 WHERE arsub.inv_num = V_WebApp_InvItem_IN_noVAT.inv_num
                                   AND arsub.[type] = 'P'
                                 GROUP BY inv_num,recpt_date,inv_seq
                                 ORDER BY convert(date,recpt_date) 
                                 FOR XML PATH ('')) , 1, 1, ''), '')
       , recpt_amount =   isnull(stuff(
                                (SELECT ',' + convert(varchar,amount)
                              FROM artran_mst arsub
                                 WHERE arsub.inv_num = V_WebApp_InvItem_IN_noVAT.inv_num
                                   AND arsub.[type] = 'P'
                                 GROUP BY inv_num,convert(date,recpt_date),amount,inv_seq
                                 ORDER BY convert(date,recpt_date) 
                                 FOR XML PATH ('')) , 1, 1, ''), '') 
 FROM V_WebApp_InvItem_IN_noVAT
 where 1=1";
        

        if (($start_inv) && ($end_inv)) {
            $query .= " AND ( inv_num between '$start_inv' AND '$end_inv' ) ";
        }
        if (($start_invdate) && ($end_invdate)) {
            $query .= " AND ( inv_date BETWEEN '$start_invdate 00:00:00' AND '$end_invdate 23:59:59' ) ";
        }
        if ($this->_item != "") {
            $query = $query . "AND ( CONCAT(item , ' ' , description)  LIKE '%" . trim($this->_item) . "%' ) ";
        }
        if ($this->_size != "") {
            $query = $query . "AND ( item_size LIKE '%" . trim($this->_size) . "%' ) ";
        }
        if ($this->_item != "") {
            $query = $query . "AND ( item LIKE '%" . trim($this->_item) . "%' ) ";
        }
        if ($this->_thick != "") {
            if (strpos($this->_thick, '-') !== false) {
                $thickExplode = explode("-", $this->_thick);
                $query = $query . "AND ( CAST(item_thick_conv as decimal(15,5))  BETWEEN   " . $thickExplode[0] . " AND  " . $thickExplode[1] . " ) ";
            } else {
                $query = $query . "AND ( CAST(item_thick_conv as decimal(15,5))  =   " . $this->_thick . "  ) ";
            }
        }
        if ($this->_width != "") {
            if (strpos($this->_width, '-') !== false) {
                $widthExplode = explode("-", $this->_width);
                $query = $query . "AND ( CAST(item_width_conv as decimal(15,5)) BETWEEN   " . $widthExplode[0] . " AND  " . $widthExplode[1] . " ) ";
            } else {
                $query = $query . "AND ( CAST(item_width_conv as decimal(15,5))  BETWEEN 0 AND  " . trim($this->_width) . "  ) ";
            }
        }

        $Customers = $this->_Customers;
        $Criteria = "";
        if (isset($Customers[0])) {
            $Criteria .= " AND ( ";
            foreach ($Customers as $ii => $rr) {
                $Criteria .= " cust_num = '$rr' OR ";
            }
            $query .= substr($Criteria, 0, -3) . " ) ";
        }

        $query = $query . " ORDER BY inv_date , inv_num , co_num, co_line , Uf_DoHdr_car_num, item";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetInvItem_INNew() {
        $start_invdate = $this->_start_invdate;
        $end_invdate = $this->_end_invdate;

        $start_inv = $this->_start_inv;
        $end_inv = $this->_end_inv;

        $item = $this->_item;

        $query = "SELECT  CONVERT(varchar,inv_date,103) as inv_date_conv ,FORMAT(AMT, 'N2') as AMT_2d ,FORMAT(VAT, 'N2') as VAT_2d,FORMAT(AMT_TOTAL, 'N2') as AMT_TOTAL_2d, *  FROM V_WebApp_InvItem_IN_NEW "
                . "WHERE acct <> '22400' and ( description <> 'เงินรับเงินมัดจำล่วงหน้า' or description is null )  ";
        if (($start_inv) && ($end_inv)) {
            $query .= " AND ( inv_num between '$start_inv' AND '$end_inv' ) ";
        }
        if (($start_invdate) && ($end_invdate)) {
            $query .= " AND ( inv_date BETWEEN '$start_invdate 00:00:00' AND '$end_invdate 23:59:59' ) ";
        }
        if ($this->_item != "") {
            $query = $query . "AND ( CONCAT(item , ' ' , description)  LIKE '%" . trim($this->_item) . "%' ) ";
        }
        if ($this->_size != "") {
            $query = $query . "AND ( item_size LIKE '%" . trim($this->_size) . "%' ) ";
        }
        if ($this->_item != "") {
            $query = $query . "AND ( item LIKE '%" . trim($this->_item) . "%' ) ";
        }
        if ($this->_thick != "") {
            if (strpos($this->_thick, '-') !== false) {
                $thickExplode = explode("-", $this->_thick);
                $query = $query . "AND ( CAST(item_thick_conv as decimal(15,5))  BETWEEN   " . $thickExplode[0] . " AND  " . $thickExplode[1] . " ) ";
            } else {
                $query = $query . "AND ( CAST(item_thick_conv as decimal(15,5))  =   " . $this->_thick . "  ) ";
            }
        }
        if ($this->_width != "") {
            if (strpos($this->_width, '-') !== false) {
                $widthExplode = explode("-", $this->_width);
                $query = $query . "AND ( CAST(item_width_conv as decimal(15,5)) BETWEEN   " . $widthExplode[0] . " AND  " . $widthExplode[1] . " ) ";
            } else {
                $query = $query . "AND ( CAST(item_width_conv as decimal(15,5))  BETWEEN 0 AND  " . trim($this->_width) . "  ) ";
            }
        }

        $Customers = $this->_Customers;
        $Criteria = "";
        if (isset($Customers[0])) {
            $Criteria .= " AND ( ";
            foreach ($Customers as $ii => $rr) {
                $Criteria .= " cust_num = '$rr' OR ";
            }
            $query .= substr($Criteria, 0, -3) . " ) ";
        }

        $query = $query . " ORDER BY inv_date , inv_num , co_num, co_line , Uf_DoHdr_car_num, item";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetReportByCust($type_sale) {
        $from_date = $this->_StartDate;
        $end_date = $this->_EndDate;

        $query1 = "";
        $query2 = "";
        $date_search = "";
        if ($from_date && $end_date) {
            $query1 = " and vv.inv_date between   '$from_date' and '$end_date' ) ";
            $query2 = " and art.inv_date between '$from_date' and '$end_date' ";
            $date_search = "  and inv_date between '$from_date' and '$end_date' ";
        }

        if ($type_sale == "EX") {
            $query = " select  at.cust_num ,ct.name  "
                    . " , SUM(amount)as sum_amt  ,sum(sales_tax) as sum_tax "
                    . " , SUM(amount) + sum(sales_tax)  as sum_total"
                    . " , SUM(amount * exch_rate) as sum_thaibarth from artran_mst at left join custaddr_mst ct  on at.cust_num = ct.cust_num and ct.cust_seq = 0 where left (at.cust_num,2) in ('EX','EB')  and type <> 'P'  ";
            $query = $query . $date_search;
            $query = $query . " group by at.cust_num ,ct.name   ";

            $query = "  select cust_num,name, SUM( isnull(cast(replace(QtyKG, ',', '') as decimal(23, 2)), 0) ) as AmountUSD, SUM( isnull(cast(replace(AMT_THB, ',', '') as decimal(23, 2)), 0) ) as AmountTHB  FROM v_webapp_invitem_EX where 1=1 "
                    . " and (inv_date between '$from_date 00:00:00' and '$end_date 23:59:59')  group by cust_num,name order by cust_num";
        } else {
            $query = "select cust_num,cust_name,sum(qtyKG) as summary_kg,sum(AMT_TOTAL) as  summary_amount  FROM V_WebApp_InvItem_IN where  inv_date between '$from_date' and '$end_date' group by cust_num ,cust_name ";
        }

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetReportByCust2021() {
        $from_date = $this->_StartDate;
        $end_date = $this->_EndDate;

        $query1 = "";
        $query2 = "";
        $date_search = "";
        if ($from_date && $end_date) {
            $query1 = " and vv.inv_date between   '$from_date' and '$end_date' ) ";
            $query2 = " and art.inv_date between '$from_date' and '$end_date' ";
            $date_search = "  and inv_date between '$from_date' and '$end_date' ";
        }


        $query = " select cust_num,cust_name,sum(isnull(qtyKG,0)) as summary_kg,sum(AMT) as summary_amount FROM V_WebApp_InvItem_IN_noVAT "
                . " where  inv_date between '$from_date' and '$end_date' group by cust_num ,cust_name ";

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetReportByEndCust($type_sale) {
        $from_date = $this->_StartDate;
        $end_date = $this->_EndDate;

        $Year = $this->_Year;
        $Month = $this->_Month;
        $cust_num = $this->cust_num;
        $cust_seq = $this->cust_seq;
        $query = "select cust_num,name,end_customer,city,cust_seq,QtyKG,AMT_USD from V_WebApp_InvItem_EX where 1=1 ";
//        if ($Year and $Month) {
//            $query = $query . "and year(inv_date) = '$Year' and month(inv_date) = '$Month' ";
//        }
        if ($cust_num) {
            $query = $query . "and (cust_num = '$cust_num' or country = '$cust_seq') ";
        }

        if ($from_date && $end_date) {
            $query = $query . "and (inv_date between '$from_date'  and '$end_date') ";
        }

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetOptionEndCust() {

        $query = "select custaddr_mst.cust_num,custaddr_mst.name from customer_mst inner join custaddr_mst on customer_mst.cust_num = custaddr_mst.cust_num 
where left(customer_mst.cust_num,2) = 'EX' group by custaddr_mst.cust_num,custaddr_mst.name";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetReportByCountry() {
        $from_date = $this->_StartDate;
        $end_date = $this->_EndDate;
        $query = "select country,"
                . " SUM( isnull(cast(replace(QtyKG, ',', '') as decimal(23, 2)), 0) ) as QtyKG,"
                . " SUM( isnull(cast(replace(AMT_THB, ',', '') as decimal(23, 2)), 0) ) as AmountTHB"
                . " FROM v_webapp_invitem_EX "
                . " where 1=1 ";
        if ($from_date && $end_date) {
            $query = $query . " and (inv_date between '$from_date 00:00:00'  and '$end_date 23:59:59')  ";
        }
        $query = $query . " group by country ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetReportByCity() {
        $from_date = $this->_StartDate;
        $end_date = $this->_EndDate;

        $query = "select cust_num ,city "
                . ",CONVERT (varchar, CAST(SUM( cast( replace(amt_usd,',','') as decimal(23,2))) AS money), 1) AS Total_USD "
                . ",CONVERT (varchar, CAST(SUM( cast( replace(AMT_THB,',','')  as decimal(23,2)))  AS money), 1) as Total_THB "
                . " from V_WebApp_InvItem_EX "
                . " where 1=1 ";

        if ($from_date && $end_date) {
            //$query = $query . " and (inv_date between '$from_date 00:00:00'  and '$end_date 23:59:59') ";
            $from_date = $from_date;
            $end_date = $end_date;
        } else {
            $from_date = "2019-07-01 ";
            $end_date = "2020-12-30 ";
        }


        $query = "select cust_num ,city ,country "
                . ",(select  CONVERT (varchar, CAST(SUM( cast( replace(amt_usd,',','') as decimal(23,2))) AS money), 1) "
                . "from V_WebApp_InvItem_EX  where inv_date between '2019-07-01 00:00:00' and '2019-07-31 23:59:59'  and cust_num = vvv.cust_num and city=vvv.city "
                . "group by city ,cust_num "
                . ") as Total_July_USD "
                . ",(select  CONVERT (varchar, CAST(SUM( cast( replace(amt_thb,',','') as decimal(23,2))) AS money), 1) "
                . "from V_WebApp_InvItem_EX  where inv_date between '2019-07-01 00:00:00' and '2019-07-31 23:59:59'  and cust_num = vvv.cust_num and city=vvv.city "
                . "group by city ,cust_num "
                . ") as Total_July_THB "
                . ",isnull((select  CONVERT (varchar, CAST(SUM( cast( replace(amt_usd,',','') as decimal(23,2))) AS money), 1) "
                . "from V_WebApp_InvItem_EX  where inv_date between '2019-08-01 00:00:00' and '2019-08-31 23:59:59' and cust_num = vvv.cust_num  and city=vvv.city "
                . "group by city ,cust_num "
                . "),0) as Total_August_USD"
                . ",isnull((select  CONVERT (varchar, CAST(SUM( cast( replace(amt_thb,',','') as decimal(23,2))) AS money), 1) "
                . "from V_WebApp_InvItem_EX  where inv_date between '2019-08-01 00:00:00' and '2019-08-31 23:59:59'  and cust_num = vvv.cust_num and city=vvv.city "
                . "group by city ,cust_num "
                . "),0) as Total_August_THB "
                . ",isnull((select  CONVERT (varchar, CAST(SUM( cast( replace(amt_usd,',','') as decimal(23,2))) AS money), 1) "
                . "from V_WebApp_InvItem_EX  where inv_date between '2019-09-01 00:00:00' and '2019-09-30 23:59:59'  and cust_num = vvv.cust_num and city=vvv.city "
                . "group by city ,cust_num "
                . "),0) as Total_September_USD "
                . ",isnull((select  CONVERT (varchar, CAST(SUM( cast( replace(amt_thb,',','') as decimal(23,2))) AS money), 1) "
                . "from V_WebApp_InvItem_EX  where 1=1 and "
                . " inv_date between '2019-09-01 00:00:00' and '2019-09-30 23:59:59'  and cust_num = vvv.cust_num and city=vvv.city "
                . "group by city ,cust_num "
                . "),0) as Total_September_THB "
                . ",CONVERT (varchar, CAST(SUM( cast( replace(amt_usd,',','') as decimal(23,2))) AS money), 1) AS Total_USD "
                . ",CONVERT (varchar, CAST(SUM( cast( replace(AMT_THB,',','')  as decimal(23,2)))  AS money), 1) as Total_THB  "
                . "from V_WebApp_InvItem_EX vvv where 1=1 and "
                . " inv_date between '$from_date 00:00:00' and '$end_date 23:59:59'  "
                . "group by city ,cust_num ,country ";


        $query = "select city,"
                . " SUM( isnull(cast(replace(amt_usd, ',', '') as decimal(23, 2)), 0) ) as amt_usd,"
                . " SUM( isnull(cast(replace(AMT_THB, ',', '') as decimal(23, 2)), 0) ) as AmountTHB"
                . " FROM v_webapp_invitem_EX "
                . " where 1=1 ";
        if ($from_date && $end_date) {
            $query = $query . " and (inv_date between '$from_date 00:00:00'  and '$end_date 23:59:59') ";
        }
        $query = $query . " group by city ";


//        $query = $query . " group by city ,cust_num ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetReportByInv() {
        $from_date = $this->_StartDate;
        $end_date = $this->_EndDate;
        $query = "select inv_num,name,"
                . " SUM( isnull(cast(replace(QtyKG, ',', '') as decimal(23, 2)), 0) ) as AmountUSD,"
                . " SUM( isnull(cast(replace(AMT_THB, ',', '') as decimal(23, 2)), 0) ) as AmountTHB"
                . " FROM v_webapp_invitem_EX "
                . " where 1=1 ";
        if ($from_date && $end_date) {
            $query = $query . " and (inv_date between '$from_date 00:00:00'  and '$end_date 23:59:59') ";
        }
        $query = $query . " group by  inv_num,name order by  inv_num";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetOptionCountry() {

        $query = "select country from custaddr_mst where country is not null and country <> 'Thailand' group by country";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetOptionSeqEndCust($cust_num) {
        $query = "select name,city,cust_seq,* from custaddr_mst where cust_num = '$cust_num' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetReportByGroup($type_sale) {
        $from_date = $this->_StartDate;
        $end_date = $this->_EndDate;

        $from_date2 = $this->_txtFromDateGroup;
        $end_date2 = $this->_txtToDateGroup;
        if ($type_sale == "EX") {

            $query = " select isnull(group_code,'Z_ORDER') as group_code , isnull(group_final,'') as group_final , SUM( isnull(cast(replace(QtyKG, ',', '') as decimal(23, 2)), 0) ) as summary_kg , SUM( isnull(cast(replace(AMT_THB, ',', '') as decimal(23, 2)), 0) ) as summary_amount  FROM v_webapp_invitem_EX where 1=1  ";
        } else {
            $query = " select isnull(group_final,'Z_ORDER') as group_code ,"
                    . " isnull(group_final,'') as group_final, SUM( isnull(cast(replace(QtyKG, ',', '') as decimal(23, 2)), 0) ) as summary_kg,"
                    . " SUM( isnull(cast(replace(AMT_TOTAL, ',', '') as decimal(23, 2)), 0) ) as summary_amount "
                    . " FROM V_WebApp_InvItem_IN where 1=1 ";
        }
        if ($from_date && $end_date) {
            $query = $query . " and (inv_date between '$from_date 00:00:00'  and '$end_date 23:59:59') ";
        }

        if ($from_date2 && $end_date2) {
            $query = $query . " and (inv_date between '$from_date2 00:00:00'  and '$end_date2 23:59:59') ";
        }
        if ($type_sale == "IN") {
            $query = $query . " group by group_final, group_final ";
        }
        if ($type_sale == "EX") {
            $query = $query . " group by group_code, group_final order by group_code ";
        }
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetReportByGroup2021() {
        $from_date = $this->_StartDate;
        $end_date = $this->_EndDate;

        $from_date2 = $this->_txtFromDateGroup;
        $end_date2 = $this->_txtToDateGroup;

        $query = " select isnull(group_final,'Z_ORDER') as group_code , isnull(group_final,'') as group_final ,"
                . " SUM( isnull(cast(replace(QtyKG, ',', '') as decimal(23, 2)), 0) ) as summary_kg, "
                . " SUM( isnull(cast(replace(AMT, ',', '') as decimal(23, 2)), 0) ) as summary_amount "
                . " FROM V_WebApp_InvItem_IN_noVAT where 1=1 ";

        if ($from_date && $end_date) {
            $query = $query . " and (inv_date between '$from_date 00:00:00'  and '$end_date 23:59:59') ";
        }

        if ($from_date2 && $end_date2) {
            $query = $query . " and (inv_date between '$from_date2 00:00:00'  and '$end_date2 23:59:59') ";
        }
        $query = $query . " group by group_final, group_final ";

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetInvItem_Edit($SwitchSale) {
        $start_inv = $this->_start_inv;
        $end_inv = $this->_end_inv;
        $SaleCriteria = "";
        if ($SwitchSale == "IN") {
            $SaleCriteria = " AND (inv_num like 'IN%' OR inv_num like 'CN%' OR inv_num like 'DN%'  )  ";
        } else if ($SwitchSale == "EX") {
            $SaleCriteria = " AND (inv_num like 'EX%' OR inv_num like 'EB%'  OR inv_num like 'CN%' OR inv_num like 'DN%'  )  ";
        }
        $query = "SELECT  * FROM V_WebApp_InvItem_All "
                . "WHERE 1=1 "
                . "  $SaleCriteria  "
                . "AND ( inv_num  BETWEEN '$start_inv' AND '$end_inv' ) ";

        $query .= " ORDER BY inv_date , inv_num  asc";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function SaveInvItem() {
        $inv_num = $this->_inv_num;
        $co_line = $this->_co_line;
        $item = $this->_item;
        $edit_um = $this->_edit_um;
        $edit_qty = $this->_edit_qty;
        $edit_price = $this->_edit_price;
        $edit_disc = $this->_edit_disc;
        $saveQtyPcs = 0;
        $saveQtyKG = 0;
        $savePricePcs = 0;
        $savePriceKG = 0;


        $query1 = "";
        $query2 = "";
        $query3 = "";
        $inv_line_count = 0;
        //========check backup has default data======//
        $query1 = " SELECT * FROM WebApp_BackDoorChange_InvItem WHERE inv_num ='$inv_num' AND co_line=$co_line AND item ='$item'  ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query1);
        array_splice($rs0, count($rs0) - 1, 1);
        $cSql = null;
        $CountRow = count($rs0);
        //===Get old value from inv_item_mst
        $query2 = "SELECT  
                         a.inv_num, inv_date, inv_line, a.inv_seq, 
                         a.co_num, co_line, item, 
                         Uf_Inv_Um,  
                         ISNULL(Uf_ActWeight,0) as Uf_ActWeight, 
                         ISNULL(Uf_NetWeight,0) as Uf_NetWeight,
                         ISNULL(Uf_PricePerKG,0) as Uf_PricePerKG ,
                         ISNULL(Uf_WeightPerPcs,0) as Uf_WeightPerPcs, 
                         ISNULL(Uf_ActQtyPcs,0) as Uf_ActQtyPcs, 
                         ISNULL(Uf_ActQtyOrder2,0)  as Uf_ActQtyOrder2 ,
                         ISNULL(qty_invoiced,0) as qty_invoiced,
                         ISNULL(a.price,0) as price
                    FROM 
                        inv_item_mst a 
                    left join inv_hdr_mst b on a.inv_num = b.inv_num 
                    WHERE a.inv_num ='$inv_num' AND co_line=$co_line AND item ='$item' ";
        $cSql = new SqlSrv();
        $oldVal = $cSql->SqlQuery($this->StrConn, $query2);
        array_splice($oldVal, count($oldVal) - 1, 1);
        $cSql = null;
        //=============================
        $UpdPrice = "";
        $UpdQty = "";
        $Result = 0;
        $inv_line_count = count($oldVal);
        $AbsVal = 0;

        if ($edit_um == "PCS") {
//            if ($inv_line_count >= 2) {
//                $AbsVal = round(($edit_qty / $inv_line_count), 4);
//            } else {
//                $AbsVal = $edit_qty;
//            }
            $saveQtyPcs = $edit_qty;
            $saveQtyKG = 0;
            $savePricePcs = $edit_price;
            $savePriceKG = 0;
        } else {
            $saveQtyPcs = 0;
            $saveQtyKG = $edit_qty;
            $savePricePcs = 0;
            $savePriceKG = $edit_price;
        }


        if ($edit_um == "PCS") {
            $UpdPrice = "";
        }

        if ($CountRow >= 1) {
            $query3 = "UPDATE WebApp_BackDoorChange_InvItem SET "
                    . " new_Inv_Um = '$edit_um' , "
                    . " new_PricePerKG = $savePriceKG , "
                    . " new_ActQtyOrder2 = $saveQtyKG , "
                    . " new_qty_invoiced = $saveQtyPcs , "
                    . " new_price = $savePricePcs "
                    . " WHERE inv_num = '$inv_num' and co_line = $co_line and item = '$item' ";
            $cSql = new SqlSrv();
            $Result = $cSql->IsUpDel($this->StrConn, $query3);
            $cSql = null;
        } else {
            //Insert new to backup

            $query3 = "Insert INTO WebApp_BackDoorChange_InvItem (
                         inv_num, inv_date, inv_line, inv_seq, 
                         co_num, co_line, item, 
                         old_Inv_Um, new_Inv_Um, 
                         old_ActWeight, new_ActWeight, 
                         old_NetWeight, new_NetWeight, 
                         old_PricePerKG,new_PricePerKG, 
                         old_WeightPerPcs, new_WeightPerPcs, 
                         old_ActQtyPcs, new_ActQtyPcs, 
                         old_ActQtyOrder2,new_ActQtyOrder2 , 
                         old_qty_invoiced , new_qty_invoiced   ,
                         old_price , new_price                      
                         ) VALUES (
                         '" . $oldVal[0]["inv_num"] . "','" . $oldVal[0]["inv_date"]->format('Y-m-d H:i:s') . "'," . $oldVal[0]["inv_line"] . "," . $oldVal[0]["inv_seq"] . ","
                    . "'" . $oldVal[0]["co_num"] . "'," . $oldVal[0]["co_line"] . ",'" . $oldVal[0]["item"] . "',"
                    . "'" . $oldVal[0]["Uf_Inv_Um"] . "','$edit_um',"
                    . "" . $oldVal[0]["Uf_ActWeight"] . ",0,"
                    . "" . $oldVal[0]["Uf_NetWeight"] . ",0,"
                    . "" . $oldVal[0]["Uf_PricePerKG"] . ",$savePriceKG,"
                    . "" . $oldVal[0]["Uf_WeightPerPcs"] . ",0,"
                    . "" . $oldVal[0]["Uf_ActQtyPcs"] . ",0,"
                    . "" . $oldVal[0]["Uf_ActQtyOrder2"] . ",$saveQtyKG,"
                    . "" . $oldVal[0]["qty_invoiced"] . ",$saveQtyPcs  ,"
                    . "" . $oldVal[0]["price"] . ",$savePricePcs                   
                    ) ";
            $cSql = new SqlSrv();
            $Result = $cSql->IsUpDel($this->StrConn, $query3);
            $cSql = null;
        }
        // ===== change inv item====== //
        $UpdateResult = 0;
        if ($Result == 1) {
            $TmpGet = null;
            $cSql = new SqlSrv();
            $sql = " SELECT * FROM  inv_item_mst WHERE  inv_num = '$inv_num' AND co_line = $co_line AND item ='$item' ";
            $TmpGet = $cSql->SqlQuery($this->StrConn, $sql);
            $cSql = null;

            $cSql = new SqlSrv();
            $addToFirstRow = $oldVal[0]["qty_invoiced"] - $saveQtyPcs;

            if ($inv_line_count >= 2) {

                $sqlUp = "UPDATE inv_item_mst  "
                        . "SET Uf_Inv_Um = '$edit_um' , "
                        . "Uf_PricePerKG  = $savePriceKG ,  "
                        . "Uf_ActQtyOrder2 = $saveQtyKG , "
                        . "qty_invoiced = qty_invoiced +  $saveQtyPcs  "
                        . "WHERE "
                        . "  inv_num = '$inv_num' AND co_line = $co_line and item= '$item' "
                        . "  AND inv_line in( "
                        . "     select min(inv_line) "
                        . "     FROM inv_item_mst ii "
                        . "     where ii.inv_num = '$inv_num' "
                        . "     and ii.co_line = '$co_line' "
                        . "     and ii.item = '$item'   "
                        . "         )";
                $UpdateResult = $cSql->IsUpDel($this->StrConn, $sqlUp);
            } else {
                $sqlUp = "UPDATE inv_item_mst "
                        . "SET Uf_Inv_Um = '$edit_um' , "
                        . "Uf_PricePerKG  = $savePriceKG ,  "
                        . "Uf_ActQtyOrder2 = $saveQtyKG , "
                        . "qty_invoiced = $saveQtyPcs  "
                        . "WHERE "
                        . "  inv_num = '$inv_num' AND co_line = $co_line and item= '$item' ";
                $UpdateResult = $cSql->IsUpDel($this->StrConn, $sqlUp);
            }
            $cSql = null;
        }
        return $UpdateResult;
    }

    function GetInvItem() {
        $start_invdate = $this->_start_invdate;
        $end_invdate = $this->_end_invdate;
        $product_code = $this->_product_code;
        $prodCriteria = "";
//        if(!$product_code){
//            $prodCriteria =" AND ( product_code = '$product_code' ) " ; 
//        }
        $query = "SELECT  * FROM V_WebApp_InvItem_IN2 "
                . "WHERE LEFT(inv_num,2)='IN' AND  (item_code <> '' ) AND ( inv_date BETWEEN '$start_invdate' AND '$end_invdate' ) $prodCriteria";
        if ($this->_item != "") {
            $query = $query . "AND ( CONCAT(item_code , ' ' , item_desc)  LIKE '%" . trim($this->_item) . "%' ) ";
        }
        if ($this->_size != "") {
            $query = $query . "AND ( item_size LIKE '%" . trim($this->_size) . "%' ) ";
        }
        if ($this->_thick != "") {
            if (strpos($this->_thick, '-') !== false) {
                $thickExplode = explode("-", $this->_thick);
                $query = $query . "AND ( CAST(item_thick_conv as decimal(15,5))  BETWEEN   " . $thickExplode[0] . " AND  " . $thickExplode[1] . " ) ";
            } else {
                $query = $query . "AND ( CAST(item_thick_conv as decimal(15,5))  =   " . $this->_thick . "  ) ";
            }
        }
        if ($this->_width != "") {
            if (strpos($this->_width, '-') !== false) {
                $widthExplode = explode("-", $this->_width);
                $query = $query . "AND ( CAST(item_width_conv as decimal(15,5)) BETWEEN   " . $widthExplode[0] . " AND  " . $widthExplode[1] . " ) ";
            } else {

                $query = $query . "AND ( CAST(item_width_conv as decimal(15,5))  BETWEEN 0 AND  " . trim($this->_width) . "  ) ";
            }
        }
        $Customers = $this->_Customers;
        $Criteria = "";
        if (isset($Customers[0])) {
            $Criteria .= " AND ( ";
            foreach ($Customers as $ii => $rr) {
                $Criteria .= " cust_num = '$rr' OR ";
            }
            $query .= substr($Criteria, 0, -3) . " ) ";
        }
//        return $query;
        $query = $query . " ORDER BY inv_date asc";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetInvItemByHdr() {
        $inv_num = $this->_inv_num;
        $query = "SELECT  * FROM V_WebApp_InvItem_IN "
                . " WHERE (item_code <> '' ) AND ( inv_num = '$inv_num' ) ";
        if ($this->_item != "") {
            $query = $query . "AND ( CONCAT(item_code , ' ' , item_desc)  LIKE '%" . trim($this->_item) . "%' ) ";
        }
        if ($this->_size != "") {
            $query = $query . "AND ( item_size LIKE '%" . trim($this->_size) . "%' ) ";
        }
        if ($this->_thick != "") {
            if (strpos($this->_thick, '-') !== false) {
                $thickExplode = explode("-", $this->_thick);
                $query = $query . "AND ( CAST(item_thick_conv as decimal(15,5))  BETWEEN   " . $thickExplode[0] . " AND  " . $thickExplode[1] . " ) ";
            } else {
                $query = $query . "AND ( CAST(item_thick_conv as decimal(15,5))  =   " . $this->_thick . "  ) ";
            }
        }
        if ($this->_width != "") {
            if (strpos($this->_width, '-') !== false) {
                $widthExplode = explode("-", $this->_width);
                $query = $query . "AND ( CAST(item_width_conv as decimal(15,5)) BETWEEN   " . $widthExplode[0] . " AND  " . $widthExplode[1] . " ) ";
            } else {

                $query = $query . "AND ( CAST(item_width_conv as decimal(15,5))  BETWEEN 0 AND  " . trim($this->_width) . "  ) ";
            }
        }
        $query = $query . " ORDER BY inv_date asc";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    Function Consolidate_Commit_Inv() {
        $result = "";
        $inv_num = $this->_inv_num;

        $callSP = "Exec SP_FixedRecalNotworkingByInv @inv_num='$inv_num' ";

        $stmt = sqlsrv_query($this->StrConn, $callSP);
        if (!$stmt) {
            $result = "ERROR !! บันทึกค่า Inv ไม่ได้ ";
            //die(print_r(sqlsrv_errors(), true));
        } else {
            $result = "บันทึกค่า Inv สำเร็จ";
        }

        // sqlsrv_free_stmt($stmt);
        return $result;
    }

    Function GetRows_SP_DepositHeader() {
        $selYear = $this->_Year;
        $selMonth = $this->_Month;
        $callSP = 'Exec SP_WebApp_InvDeposit_Header @year=?,@month=?';
        $params = array($selYear, $selMonth);
        $stmt = sqlsrv_query($this->StrConn, $callSP, $params);
        if ($stmt === false) {
            echo "Error in executing statement 3.\n";
            die(print_r(sqlsrv_errors(), true));
        }
        $ArrTmp = array();
        $ArrResult = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $ArrResult["id"] = $row['id'];
            $ArrResult["inv_date"] = $row['inv_date'] ? $row['inv_date']->format('Y-m-d') : "";
            $ArrResult["inv_num"] = $row['inv_num'];
            $ArrResult["inv_ref"] = $row['inv_ref'];
            $ArrResult["cust_num"] = $row['cust_num'];
            $ArrResult["description"] = $row['description'];
            $ArrResult["amount_deposit_total"] = number_format($row['amount_deposit_total'], 2);
            $ArrResult["amount"] = number_format($row['amount'], 2);
            $ArrResult["tax"] = number_format($row['tax'], 2);
            $ArrResult["amount_remain"] = number_format($row['amount_remain'], 2);
            array_push($ArrTmp, $ArrResult);
        }

        sqlsrv_free_stmt($stmt);
        return $ArrTmp;
    }

    Function GetRows_SP_DepositHeaderSummary() {
        $query = "select CONVERT(varchar,inv_date,11) as inv_date_con ,* from V_WebApp_DepositHeader_Result";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetReportSalesTotal() {
        $from_date = $this->_StartDate;
        $end_date = $this->_EndDate;
        $query = " select inv_date , inv_num , cust_num , name , item , description , item_size , item_thick , item_width , item_length , unit_weight , group_code , group_final , um , sum(cast(replace(isnull(qtyPCS,0),',','') as decimal(18,2) ) ) as SUMqtyPCS , priceperpcs , sum(cast(replace(isnull(qtykg,0),',','') as decimal(18,2) ) ) as SUMqtyKG , isnull(priceperkg,0) , priceperton , sum(cast(replace(isnull(discount,0),',','') as decimal(18,2) ) ) as SUMdiscount , sum(cast(replace(isnull(amt_usd,0),',','') as decimal(18,2) ) ) as SUMamt_sd , exch_rate , sum(cast(replace(isnull(amt_thb,0),',','') as decimal(18,2) ) ) as SUMamt_thb , sum(cast(replace(isnull(VAT,0),',','') as decimal(18,2) ) ) as SUMvat , country from ( select inv_date , inv_num , cust_num , name , item , description , item_size , item_thick , item_width , item_length , isnull( unit_weight ,0) as unit_weight , group_code , group_final , um , (cast(replace(isnull(qtyPCS,0),',','') as decimal(18,2) ) ) as qtyPCS , (cast(replace(isnull(priceperpcs,0),',','') as decimal(18,2) ) ) priceperpcs , (cast(replace(isnull(qtykg,0),',','') as decimal(18,2) ) ) as qtykg , (cast(replace(isnull(priceperkg,0),',','') as decimal(18,2) ) ) as priceperkg , (cast(replace(isnull(priceperton,0),',','') as decimal(18,2) ) ) as priceperton , (cast(replace(isnull(discount,0),',','') as decimal(18,2) ) ) as discount , (cast(replace(isnull(amt_usd,0),',','') as decimal(18,2) ) ) as amt_usd , exch_rate , (cast(replace(isnull(amt_thb,0),',','') as decimal(18,2) ) ) as amt_thb , 0 as VAT , country FROM V_WebApp_InvItem_EX ";
        if ($from_date && $end_date) {
            $query = $query . " where inv_date between '$from_date 00:00:00.000' and '$end_date 23:59:59.000'  ";
        }
        $query = $query . " UNION select inv_date , inv_num , cust_num , name , item , description , '' as item_size , '' as item_thick , '' as item_width , '' as item_length , 0 as unit_weight , '' as group_code , '' as group_final , um , (cast(replace(isnull(qtyPCS,0),',','') as decimal(18,2) ) ) as qtyPCS ,(cast(replace(isnull(priceperpcs,0),',','') as decimal(18,2) ) ) as priceperpcs , (cast(replace(isnull(qtykg,0),',','') as decimal(18,2) ) ) as qtykg , (cast(replace(isnull(priceperkg,0),',','') as decimal(18,2) ) ) priceperkg , 0 as priceperton , (cast(replace(isnull(discount,0),',','') as decimal(18,2) ) ) as discount , 0 as amt_usd , 0 as exch_rate , (cast(replace(isnull(AMT,0),',','') as decimal(18,2) ) ) as amt_thb , (cast(replace(isnull(VAT,0),',','') as decimal(18,2) ) ) as VAT , country = 'TH' FROM V_WebApp_InvItem_IN ";
        if ($from_date && $end_date) {
            $query = $query . " where inv_date between '$from_date 00:00:00.000' and '$end_date 23:59:59.000' ) as sumTMP ";
        }
        $query = $query . " group by inv_date , inv_num , cust_num , name , item , description , item_size , exch_rate , item_thick , item_width , item_length , unit_weight , group_code , group_final , um , priceperpcs , priceperkg , priceperton , country";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetInvItem_ALL_NEW($FromDate, $ToDate) {
//                    $query = "  select 'ขายในประเทศ' as type_sale ,inv_date, inv_num , cust_num , name , item ,description,unit_weight, group_final   ,um ,qtypcs ,priceperpcs ,  qtykg, PricePerKG , amt , vat , amt_total from v_webapp_invitem_in "
//                . " where inv_date between '$FromDate' and '$ToDate' "
//                . " union "
//                . " select 'ขายต่างประเทศ'  as type_sale  ,inv_date, inv_num , cust_num , name , item ,description,unit_weight, group_final   ,um ,qtypcs ,priceperpcs ,  qtykg, PricePerKG , AMT_THB as amt, 0 as vat , AMT_THB  as amt_total "
//                . " from v_webapp_invitem_ex "
//                . " where inv_date between '$FromDate' and '$ToDate' ";
        //$query = "select inv_date , inv_num , cust_num , name , item , description , item_size , item_thick , item_width , item_length , unit_weight , group_code , group_final , um , (cast(replace(isnull(qtyPCS,0),',','') as decimal(18,2) ) ) as qtyPCS , priceperpcs , (cast(replace(isnull(qtykg,0),',','') as decimal(18,2) ) ) as qtyKG , priceperkg , priceperton , (cast(replace(isnull(discount,0),',','') as decimal(18,2) ) ) as discount , (cast(replace(isnull(amt_usd,0),',','') as decimal(18,2) ) ) as amt_sd , exch_rate , (cast(replace(isnull(amt_thb,0),',','') as decimal(18,2) ) ) as amt_thb , (cast(replace(isnull(VAT,0),',','') as decimal(18,2) ) ) as vat , country , custPO, uf_NPS, uf_theoryweightperitem, uf_schedule from ( select inv_date , inv_num , cust_num , name , item , description , item_size , item_thick , item_width , item_length , isnull( unit_weight ,0) as unit_weight , group_code , group_final , um , (cast(replace(isnull(qtyPCS,0),',','') as decimal(18,2) ) ) as qtyPCS , (cast(replace(isnull(priceperpcs,0),',','') as decimal(18,2) ) ) priceperpcs , (cast(replace(isnull(qtykg,0),',','') as decimal(18,2) ) ) as qtykg , (cast(replace(isnull(priceperkg,0),',','') as decimal(18,2) ) ) as priceperkg , (cast(replace(isnull(priceperton,0),',','') as decimal(18,2) ) ) as priceperton , (cast(replace(isnull(discount,0),',','') as decimal(18,2) ) ) as discount , (cast(replace(isnull(amt_usd,0),',','') as decimal(18,2) ) ) as amt_usd , exch_rate , (cast(replace(isnull(amt_thb,0),',','') as decimal(18,2) ) ) as amt_thb , 0 as VAT , country , custPO, uf_NPS, uf_theoryweightperitem, uf_schedule FROM V_WebApp_InvItem_EX where inv_date between '2019-01-01 00:00:00.000' and '2019-09-30 23:59:59.000' UNION select inv_date , inv_num , cust_num , name , item , description , item_size , item_thick , item_width , item_length , unit_weight , group_code , group_final , um , (cast(replace(isnull(qtyPCS,0),',','') as decimal(18,2) ) ) as qtyPCS ,(cast(replace(isnull(priceperpcs,0),',','') as decimal(18,2) ) ) as priceperpcs , (cast(replace(isnull(qtykg,0),',','') as decimal(18,2) ) ) as qtykg , (cast(replace(isnull(priceperkg,0),',','') as decimal(18,2) ) ) priceperkg , 0 as priceperton , (cast(replace(isnull(discount,0),',','') as decimal(18,2) ) ) as discount , 0 as amt_usd , 0 as exch_rate , (cast(replace(isnull(AMT,0),',','') as decimal(18,2) ) ) as amt_thb , (cast(replace(isnull(VAT,0),',','') as decimal(18,2) ) ) as VAT , country = 'TH' , custPO, uf_NPS, uf_theoryweightperitem, uf_schedule FROM V_WebApp_InvItem_IN where inv_date between '2019-01-01 00:00:00.000' and '2019-09-30 23:59:59.000' ) as sumTMPselect ";

        $query = "  select 'ขายในประเทศ' as type_sale ,inv_date, inv_num , cust_num , name , item ,description,unit_weight, group_final   ,um ,qtypcs ,priceperpcs ,  qtykg, PricePerKG , amt , vat , amt_total "
                . " from v_webapp_invitem_in "
                . " where inv_date between '$FromDate' and '$ToDate' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);

        $query2 = " select 'ขายต่างประเทศ'  as type_sale  ,inv_date, inv_num , cust_num , name , item ,description,unit_weight, group_final   ,um ,qtypcs ,priceperpcs ,  qtykg, PricePerKG , AMT_THB as amt, 0 as vat , AMT_THB  as amt_total "
                . " from v_webapp_invitem_ex "
                . " where inv_date between '$FromDate' and '$ToDate' ";
        $cSql = new SqlSrv();
        $rs1 = $cSql->SqlQuery($this->StrConn, $query2);
        array_splice($rs1, count($rs1) - 1, 1);
        $result = array_merge($rs0, $rs1);
        return $result;
    }

    function RPTInvoiceCheck() {
        $query = " select * FROM xxx ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function RPT_payment_invoice_checking($InvFromDate, $InvToDate, $inv_num, $cust_num, $date_dif, $credit_term, $type) {
        $query = " select distinct art.inv_num , art.cust_num , cadd.name , "
                . " CASE WHEN LEFT(inv_num, 2) = 'CN' THEN art.inv_amount *-1 ELSE art.inv_amount END AS inv_amount ,"
                . " art.paid_amount , CONVERT(varchar(10),art.inv_date,120) as inv_date ,"
                . " CONVERT(varchar(10),art.recpt_date,120) as RECEIVEdate ,"
                . " datedif = datediff(day,art.inv_date,art.recpt_date) ,"
                . " convert(varchar(10),art.due_date,120) as Due_date,term.due_days as credit_term "
                . " from V_WebApp_ArtranPaidAmount art "
                . " LEFT JOIN customer_mst cust ON art.cust_num = cust.cust_num and cust.cust_seq= 0 "
                . " LEFT JOIN terms_mst term ON cust.terms_code = term.terms_code "
                . " LEFT JOIN custaddr_mst cadd ON art.cust_num = cadd.cust_num "
                . " and cadd.cust_seq= 0 where 1=1 ";

        if ($InvFromDate != "" && $InvFromDate != "") {
            $query = $query . " and inv_date BETWEEN '$InvFromDate 00:00:00.000' and '$InvToDate 23:59:59.000'";
        }
        if ($inv_num != "") {
            $query = $query . " and art.inv_num like '%$inv_num%' ";
        }
        if ($cust_num != "") {
            $query = $query . " and (art.cust_num like '%$cust_num%' or cadd.name like '%$cust_num%' )";
        }
        if ($date_dif != "" && $date_dif != 0) {
            $query = $query . " and datediff(day,art.inv_date,art.recpt_date) >= '$date_dif' ";
        }
        if ($credit_term != "") {
            $query = $query . " and term.due_days = '$credit_term' ";
        }
        if ($type != "") {
            $query = $query . " and art.type = '$type' ";
        }
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function RPT_SALES_EX_ITEMS($InvFromDate, $InvToDate, $inv_num, $cust_num, $item, $country) {
        $query = " select *,CONVERT(varchar(10),inv_date,120) as inv_date  FROM STS_sales_EX where 1=1 ";

        if ($InvFromDate != "" && $InvFromDate != "") {
            $query = $query . " and inv_date BETWEEN '$InvFromDate 00:00:00.000' and '$InvToDate 23:59:59.000'";
        }
        if ($inv_num != "") {
            $query = $query . " and inv_num like '%$inv_num%' ";
        }
        if ($cust_num != "") {
            $query = $query . " and (cust_num like '%$cust_num%' or name like '%$cust_num%' )";
        }
        if ($item != "") {
            $query = $query . " and item like '%$item%' ";
        }
        if ($country != "") {
            $query = $query . " and country like '%$country%' ";
        }
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function RPT_SALES_IN_ITEMS($InvFromDate, $InvToDate, $inv_num, $cust_num, $item) {
        $query = " select *,CONVERT(varchar(10),inv_date,120) as inv_date  FROM STS_sales_IN where 1=1 ";

        if ($InvFromDate != "" && $InvFromDate != "") {
            $query = $query . " and inv_date BETWEEN '$InvFromDate 00:00:00.000' and '$InvToDate 23:59:59.000'";
        }
        if ($inv_num != "") {
            $query = $query . " and inv_num like '%$inv_num%' ";
        }
        if ($cust_num != "") {
            $query = $query . " and (customer like '%$cust_num%' or cust_name like '%$cust_num%' )";
        }
        if ($item != "") {
            $query = $query . " and item like '%$item%' ";
        }

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function Getinv_num($from_invnum, $to_invnum) {
        $query = "select inv_hdr_mst.inv_num,
        rtrim(ltrim(STS_AD_Entry_Summary.temp)) as temp,
        STS_AD_Entry_Summary.amount_FOB,
		STS_AD_Entry_Summary.sales_term,
		CONVERT (varchar , STS_AD_Entry_Summary.BL_date ,120) as BL_date,

		STS_AD_Entry_Summary.entry_num1,
		STS_AD_Entry_Summary.entry_num2,
        STS_AD_Entry_Summary.entry_type1,
        STS_AD_Entry_Summary.entry_type2,
		STS_AD_Entry_Summary.port_code1,
		STS_AD_Entry_Summary.port_code2,
		CONVERT (varchar , STS_AD_Entry_Summary.ENTDATEU1 ,120) as ENTDATEU1,
		CONVERT (varchar , STS_AD_Entry_Summary.ENTDATEU2 ,120) as ENTDATEU2,
		STS_AD_Entry_Summary.BL_num1,
        STS_AD_Entry_Summary.BL_num2,
		STS_AD_Entry_Summary.importer1,
		STS_AD_Entry_Summary.importer2,
		STS_AD_Entry_Summary.AD_rate1,
		STS_AD_Entry_Summary.AD_rate2,
		STS_AD_Entry_Summary.AD_amount1,
		STS_AD_Entry_Summary.AD_amount2,
		STS_AD_Entry_Summary.ENTVALUE1,
		STS_AD_Entry_Summary.ENTVALUE2,
		CONVERT (varchar , STS_AD_Entry_Summary.pay_date1 ,120) as pay_date1,
		STS_AD_Entry_Summary.amount_rec1,
		CONVERT (varchar , STS_AD_Entry_Summary.pay_date2 ,120) as pay_date2,
		STS_AD_Entry_Summary.amount_rec2,
		CONVERT (varchar , STS_AD_Entry_Summary.pay_date3 ,103) as pay_date3,
		STS_AD_Entry_Summary.amount_rec3
        from inv_hdr_mst
		left join STS_AD_Entry_Summary
		ON inv_hdr_mst.inv_num = STS_AD_Entry_Summary.inv_num
        where inv_hdr_mst.inv_num like 'EX%' and inv_hdr_mst.inv_num  BETWEEN '$from_invnum' and '$to_invnum' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
        // CONVERT (varchar , STS_AD_Entry_Summary.ship_date ,120) as ship_date,
    }

    function Insertinv_num($inv_num, $col_name, $valdata) {

        $queryinv = "select * 
        from STS_AD_Entry_Summary where inv_num  = '$inv_num' ";
        $cSql1 = new SqlSrv();
        $rs01 = $cSql1->SqlQuery($this->StrConn, $queryinv);

        if ( $inv_num == isset($rs01[1]["inv_num"])){
            $query = "UPDATE STS_AD_Entry_Summary SET $col_name = '$valdata'  WHERE inv_num ='$inv_num' ";
            $cSql = new SqlSrv();
            $rs0 = $cSql->SqlQuery($this->StrConn, $query);
            array_splice($rs0, count($rs0) - 1, 1);
            return $rs0;
        } else {
            $query = "INSERT INTO STS_AD_Entry_Summary (inv_num,$col_name) VALUES ('$inv_num','$valdata')";
            $cSql = new SqlSrv();
            $rs0 = $cSql->SqlQuery($this->StrConn, $query);
            array_splice($rs0, count($rs0) - 1, 1);
            return $rs0;
        }
      
    }

    function Report_AD_All_Temp($from_invnum, $to_invnum, $fromDate, $toDate) {

        // $fromDate == "" ? $fromDate = 'NUll' : $fromDate = "$fromDate";
        // $toDate == "" ? $toDate = 'NUll' : $toDate = "$toDate";
        // $from_invnum == "" ? $from_invnum = 'NUll' : $from_invnum = "$from_invnum";
        // $to_invnum == "" ? $to_invnum = 'NUll' : $to_invnum = "$to_invnum";
        

        $query = "EXEC [dbo].[STS_AD_AllTemp]
        @vSite = STS,
        @vInvStr = '$from_invnum',
        @vInvEnd = '$to_invnum',
        @vDateStr = '$fromDate',
        @vDateEnd = '$toDate',
        @vCustStr = NULL,
        @vCustEnd = NULL ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

}
