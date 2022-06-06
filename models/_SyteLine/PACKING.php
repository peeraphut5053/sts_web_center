<?php

class PACKING {

    var $StrConn = "";
    public $_start_Tagdate = ""; //
    public $_end_Tagdate = ""; //
    public $_start_Tag = ""; //
    public $_end_Tag = ""; //
    public $_STS_PO = ""; //
    public $_TYPE = ""; //
    public $_NPS = ""; //
    public $_PcsPerBundle = ""; //
    public $_Weight = ""; //
    public $_Bundle = ""; //
    public $_HeatNo = ""; //
    public $_name = ""; //
    public $_cust_po = ""; //
    public $_PORT = ""; //
    public $_ITEM = ""; //
    public $_SIZE = ""; //
    public $_JOB = ""; //
    public $_LOT = ""; //
    public $_loc = ""; //

    function setConn($c) {
        $this->StrConn = $c;
    }

    function GetPackingListItem() {
        $start_Tagdate = $this->_start_Tagdate;
        $end_Tagdate = $this->_end_Tagdate;
        $start_Tag = $this->_start_Tag;
        $end_Tag = $this->_end_Tag;


        $criteria_Tag = "";
        if (($start_Tag) && ($end_Tag)) {
            $criteria_Tag = " AND ( tag between '$start_Tag' AND '$end_Tag' ) ";
        }
        $criteria_date = " ";
        if (($start_Tagdate) && ($end_Tagdate)) {
            $criteria_date = " AND ( print_date_conv BETWEEN '$start_Tagdate' AND '$end_Tagdate' ) ";
        }

        $query = "SELECT distinct top 10000 * FROM V_WebApp_PackingReport "
                . "WHERE   1=1   "
                . " $criteria_Tag "
                . " $criteria_date ";
        if ($this->_STS_PO != "") {
            $query = $query . "AND ( STS_PO LIKE '%" . trim($this->_STS_PO) . "%' ) ";
        }
        if ($this->_TYPE != "") {
            $query = $query . "AND ( TYPE LIKE '%" . trim($this->_TYPE) . "%' ) ";
        }
        if ($this->_NPS != "") {
            $query = $query . "AND ( NPS LIKE '%" . trim($this->_NPS) . "%' ) ";
        }
        if ($this->_PcsPerBundle != "") {
            $query = $query . "AND ( PcsPerBundle LIKE '%" . trim($this->_PcsPerBundle) . "%' ) ";
        }
        if ($this->_Weight != "") {
            $query = $query . "AND ( Weight LIKE '%" . trim($this->_Weight) . "%' ) ";
        }
        if ($this->_Bundle != "") {
            $query = $query . "AND ( Bundle LIKE '%" . trim($this->_Bundle) . "%' ) ";
        }
        if ($this->_HeatNo != "") {
            $query = $query . "AND ( Heat_No LIKE '%" . trim($this->_HeatNo) . "%' ) ";
        }
        if ($this->_name != "") {
            $query = $query . "AND ( name LIKE '%" . trim($this->_name) . "%' ) ";
        }
        if ($this->_cust_po != "") {
            $query = $query . "AND ( cust_po LIKE '%" . trim($this->_cust_po) . "%' ) ";
        }
        if ($this->_PORT != "") {
            $query = $query . "AND ( PORT LIKE '%" . trim($this->_PORT) . "%' ) ";
        }
        if ($this->_ITEM != "") {
            $query = $query . "AND ( item LIKE '%" . trim($this->_ITEM) . "%' ) ";
        }
        if ($this->_SIZE != "") {
            $query = $query . "AND ( SIZE LIKE '%" . trim($this->_SIZE) . "%' ) ";
        }
        if ($this->_JOB != "") {
            $query = $query . "AND ( job LIKE '%" . trim($this->_JOB) . "%' ) ";
        }
        if ($this->_LOT != "") {
            $query = $query . "AND ( lot LIKE '%" . trim($this->_LOT) . "%' ) ";
        }
        if ($this->_loc != "") {
            $query = $query . "AND ( loc LIKE '%" . trim($this->_loc) . "%' ) ";
        }
        $query = $query . " ORDER BY Tag  asc";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

}
