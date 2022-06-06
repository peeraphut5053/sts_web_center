<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Ship
 *
 * @author webcenter
 */
class V_MV_GRN {
    var $StrConn = "";
    public $_site_ref = "";
    public $_lot = "";
    public $_grn_num = "";
    public $UF_manu = "";
    public $_po_num = "" ; 
    function setConn($c) {
        $this->StrConn = $c;
    }
    function GetProperties() {
        $query = "SELECT *, CONVERT(VARCHAR(16), grn_hdr_date, 120)  AS grn_hdr_date  FROM MV_GRN WHERE lot = '" . $this->_lot . "' AND po_num = '" . $this->_po_num . "'  AND grn_num = '" . $this->_grn_num . "' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
//        if(!$rs0[1]["UF_manu"]){
//            
//        }
        if($rs0 !=1){
              $this->UF_manu = "";
        }else{
              $this->UF_manu = $rs0[1]["UF_manu"];
        }
      
    }

}
