<?php

// t-sql for get column name
//
// declare @aa varchar (MAX)
// set @aa = ''
//
// select @aa =
//     case when @aa = ''
//     then COLUMN_NAME
//     else @aa + coalesce(',' + COLUMN_NAME, '')
//     end
//   from Train20_AppDemo.INFORMATION_SCHEMA.COLUMNS
// WHERE TABLE_NAME = N'item_mst'
// print @aa
class CustomerSaleOrder {

    var $StrConn = "";
    public $_cust_num = "";
    public $id = "";
    public $cust_num = "";
    public $name = "";
    public $cust_num_sl = "";
    public $addr1 = "";
    public $addr2 = "";
    public $addr3 = "";
    public $tel1 = "";
    public $tel2 = "";
    public $fax1 = "";
    public $fax2 = "";
    public $measure = "";
    public $measure2 = "";
    public $measure_desc_en = "";
    public $measure_desc_th = "";
    public $import_form = "";
    public $country = "";
    private $MainQuery = "SELECT cs.id,cust_num,name,cust_num_sl,addr1,addr2,addr3,tel1,tel2,"
            . " fax1,fax2,ms.id as measure,ms.measure as measure2 ,measure_desc_en,measure_desc_th ,country,import_form "
            . " FROM SO_Customer cs "
            . " LEFT JOIN STS_MT_Measure ms ON cs.measure = ms.measure ";

    function setConn($c) {
        $this->StrConn = $c;
    }

    function GetRows($sWhere) {
        $query = $this->MainQuery . "  $sWhere ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetItemToDropdown($sWhere) {
        $query = $this->MainQuery . "  $sWhere ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        $returnArray = array();
        $tmpKey = "";
        $tempValue = "";
        $returnArray[""] = "";
        foreach ($rs0 as $index => $rows) {
            $tmpKey = $rows["cust_num"];
            $tempValue = $rows["name"];
            $returnArray[$tmpKey] = $tempValue;
        }
        return $returnArray;
    }

    function GetPropertyByVendnum() {
        $cust_num = $this->_cust_num;
        $query = $this->MainQuery . "  WHERE cust_num = '$cust_num'  ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        $this->id = $rs0[1]["id"];
        $this->cust_num = $rs0[1]["cust_num"];
        $this->name = $rs0[1]["name"];
        $this->cust_num_sl = $rs0[1]["cust_num_sl"];
        $this->addr1 = $rs0[1]["addr1"];
        $this->addr2 = $rs0[1]["addr2"];
        $this->addr3 = $rs0[1]["addr3"];
        $this->tel1 = $rs0[1]["tel1"];
        $this->tel2 = $rs0[1]["tel2"];
        $this->fax1 = $rs0[1]["fax1"];
        $this->fax2 = $rs0[1]["fax2"];
        $this->measure = $rs0[1]["measure_desc_en"];
        $this->measure2 = $rs0[1]["measure"];
        $this->country = $rs0[1]["country"];
        $this->import_form = $rs0[1]["import_form"];
        $this->measure_desc_en = $rs0[1]["measure_desc_en"];
        $this->measure_desc_th = $rs0[1]["measure_desc_th"];
    }

    function GetProperty($sWhere) {
        $query = "SELECT * FROM v_customer_info   $sWhere ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        $this->id = $rs0[1]["id"];
        $this->cust_num = $rs0[1]["cust_num"];
        $this->name = $rs0[1]["name"];
        $this->cust_num_sl = $rs0[1]["cust_num_sl"];
        $this->addr1 = $rs0[1]["addr1"];
        $this->addr2 = $rs0[1]["addr2"];
        $this->addr3 = $rs0[1]["addr3"];
        $this->tel1 = $rs0[1]["tel1"];
        $this->tel2 = $rs0[1]["tel2"];
        $this->fax1 = $rs0[1]["fax1"];
        $this->fax2 = $rs0[1]["fax2"];
        $this->measure = $rs0[1]["measure_id"];
        $this->measure_desc_en = $rs0[1]["measure_desc_en"];
        $this->measure_desc_th = $rs0[1]["measure_desc_th"];
        $this->country = $rs0[1]["country"];
        $this->import_form = $rs0[1]["import_form"];
        $this->measure_desc_en = $rs0[1]["measure_desc_en"];
        $this->measure_desc_th = $rs0[1]["measure_desc_th"];
    }

    function GetFormXlsListCust($id) {

        $query = "SELECT  *  FROM SO_Customer  WHERE import_form = $id ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);


        return $rs0;
    }

    function GetFormXlsListAll() {

        $query = "SELECT  *  FROM Xls_Form_Import ORDER BY id ASC";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);


        return $rs0;
    }

    function GetFormXlsListOne($id) {
        $query = "SELECT  *  FROM Xls_Form_Import WHERE id = $id";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

}
