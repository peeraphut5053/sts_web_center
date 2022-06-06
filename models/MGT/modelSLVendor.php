<?php

class SLVendor {
    var $StrConn = "";
    public $_site_ref = "";
    public $_vend_num = "";
    public $site_ref = "";
    public $vend_num = "";
    public $contact = "";
    public $phone = "";
    public $vend_type = "";
    public $terms_code =  "";
    public $ship_code =   "";
    public $fob =   "";
    private $AllField = " distinct vd.site_ref,  vd.vend_num, name,  contact,  phone,  vend_type,  terms_code,  ship_code,  fob,  print_price,  stat,  last_purch,  last_paid,  purch_ytd,  purch_lst_yr,  disc_ytd,  disc_lst_yr,  pay_ytd,  vend_remit,  whse ";
    function setConn($c) {
        $this->StrConn = $c;
    }
    function AjaxGetRowsWithCond($sWhere) {
    $q = "SELECT   DISTINCT vd.site_ref,  vd.vend_num, name, [addr##1] + [addr##2] + [addr##3] +[addr##4] as addrfull "
            . " FROM vendor_mst vd "
            . " LEFT JOIN vendaddr_mst vda ON (vd.vend_num = vda.vend_num) "
            . " WHERE $sWhere " ;
    $cSql = new SqlSrv();

    $rs0 = $cSql->SqlQuery($this->StrConn, $q);
    array_splice($rs0, count($rs0) - 1, 1);
    $arr = array() ;
    $ret = "" ;
    $bgColor = "" ;
    foreach($rs0 as $index => $rows){
        $index % 2== 0 ? $bgColor = "#ffffff" :$bgColor = "#f8f8f8" ;
        
        $ret = $ret . "<div class='row row-data-ajax'style='background-color:$bgColor;' >"
                . "<div class='col-1 col-data-ajax'>"
                . "<a id='sel_".$rows["vend_num"]."' data-vend-num='".$rows["vend_num"]."'  href='#'><i class='fa fa-chevron-left'></i></a>"
                . "</div>"
                 . "<div class='col-11 col-data-ajax'>".$rows["vend_num"]. " - " .$rows["name"]. " </div>"
                . "</div>" ;
    }
    return $ret;
}

    function AjaxGetItemsDropdown() {
    $q = "SELECT DISTINCT    vend_num  FROM vendor_mst ORDER BY vend_num  ";
    $cSql = new SqlSrv();

    $rs0 = $cSql->SqlQuery($this->StrConn, $q);
    array_splice($rs0, count($rs0) - 1, 1);
    $arr = array() ;
  
    foreach($rs0 as $index => $rows){
        array_push($arr,$rows["vend_num"]);
    }
    return $arr;
}


    function Ajax_GetRows_Limit($limit) {
        $query = "SELECT TOP $limit $AllField  FROM vendor_mst " ;
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
  function GetVendorByVenNum($vrn_num) {
    $q = "SELECT ".$this->AllField . " FROM vendor_mst WHERE ven_num = '$ven_num'  " ;
    $cSql = new SqlSrv();
    $rs0 = $cSql->SqlQuery($this->StrConn, $q);
    $this->site_ref = $rs0[1]["site_ref"];
    $this->vend_num = $rs0[1]["vend_num"];
    $this->contact = $rs0[1]["contact"];
    $this->phone = $rs0[1]["phone"];
  }



}
