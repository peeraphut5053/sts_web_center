<?php

class SLPOI {

    var $StrConn = "";
    public $_site_ref = "";
    public $_po_num = "";
    public $_po_line = "";
    public $_grn_num = "";
    public $_lot = "";
    public $po_num = "";
    public $po_line = "";
    public $item = "";
    public $qty = "";
    public $u_m = "";
    public $vend_num = "";
    public $vend_name = "";
    public $uf_grade = "";
    public $grn_hdr_date = "";
    public $uf_manu = "";
    public $qty_shipped_conv = "";
    public $grn_line = "";
    public $query = "";
    public $found_item = "";
    public $uf_width = "";

    function setConn($c) {
        $this->StrConn = $c;
    }

    function GetPOData() {
      $vend_num ="" ;
        $q = "SELECT  uf_width,qty_shipped_conv,grh.grn_hdr_date,grn_line,item.uf_grade, poi.po_num as po_num, poi.po_line , poi.item  as item , qty_ordered_conv ,poi.u_m ,vd.vend_num , va.name "
                . "FROM poitem_mst poi "
                . "LEFT JOIN po_mst po ON (poi.po_num = po.po_num) "
                . "LEFT JOIN vendor_mst vd ON (po.vend_num = vd.vend_num) "
                . "LEFT JOIN vendaddr_mst  va ON (va.vend_num = vd.vend_num) "
                . "LEFT JOIN item_mst  item ON (item.item = poi.item) "
                . "LEFT JOIN grn_line_mst grn ON  (grn.po_num = po.po_num and grn.po_line = poi.po_line) "
                . "LEFT JOIN grn_hdr_mst grh ON  (grh.grn_num = grn.grn_num) "
                . "WHERE uf_lot='" . $this->_lot . "' AND grn.grn_num = '" . $this->_grn_num . "' AND poi.po_num = '" . $this->_po_num . "' AND poi.po_line =  " . $this->_po_line;
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $q);
        array_splice($rs0, count($rs0) - 1, 1);
         // echo $q;
//        return $q;
//        if ($rs0) {
        $rs0[0]["vend_num"]?$vend_num =trim($rs0[0]["vend_num"]) : $vend_num="";
        $this->query = $q;
        $this->foud_item = count($rs0);
        $this->po_num = $rs0[0]["po_num"];
        $this->po_line = $rs0[0]["po_line"];
        $this->item = $rs0[0]["item"];
        $this->qty_shipped_conv = $rs0[0]["qty_shipped_conv"];
        $this->qty = $rs0[0]["qty_ordered_conv"];
        $this->u_m = $rs0[0]["u_m"];
        $this->vend_num = $vend_num;
        $this->grn_hdr_date = $rs0[0]["grn_hdr_date"];
        $this->vend_name = $rs0[0]["name"];
//         $this->uf_width  = $rs0[0]["uf_width"];
        $rs0[0]["uf_width"] != null ? $this->uf_width = $rs0[0]["uf_width"] : $this->uf_width = "";
        $rs0[0]["uf_grade"] != null ? $this->uf_grade = $rs0[0]["uf_grade"] : $this->uf_grade = "";
        $this->grn_line = $rs0[0]["grn_line"];
//        }else{
//              $this->query ;
//        }
//        return $ret;
    }

}
