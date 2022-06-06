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
class OrderDetail {

    var $StrConn = "";
//    public $_po_num = "";
    public $_doc_no = "";
    public $_item = array();
    public $_line = array();
    public $_lot = array();
    public $_item_desc = array();
    public $_size = array();
    public $_od = array();
    public $_theo_weight = array();
    public $_length = array();
    public $_length_total = array();
    public $_category = array();
    public $_sched = array();
    public $_pcs_per_bundle = array();
    public $_bundles = array();
    public $_pieces = array();
    public $_net_tons = array();
    public $_m_tons = array();
    public $_unitweights = array();
    public $_cfr_lo_mt = array();
    public $_cfr_lo_ft = array();
    public $_ext_price = array();
    public $__doc_no = "";
    public $__item = "";
    public $__line = "";
    public $__lot = "";
    public $__itemdesc = "";
    public $__size = "";
    public $__od = "";
    public $__theoweight = "";
    public $__length = "";
    public $__lengthtotal = "";
    public $__category = "";
    public $__sched = "";
    public $__pcsperbundle = "";
    public $__bundles = "";
    public $__pieces = "";
    public $__nettons = "";
    public $__mtons = "";
    public $__unitweights = "";
    public $__cfrlomt = "";
    public $__cfrloft = "";
    public $__extprice = "";
    public $__sl_co = "";
    public $__sl_item = "";
    
    
    private $AllFields = "  or_id,doc_no, line, item, item_desc, category, size, od, "
            . "theo_weight, length, length_total, sched, pcs_per_bundle, bundles, pieces, "
            . "net_tons, m_tons, cfr_lo_mt, cfr_lo_ft, ext_price ,lot,unit_weight";

    function setConn($c) {
        $this->StrConn = $c;
    }

    function Update() {
        $doc_no = $this->__doc_no;
        $item = $this->__item;
        $item_desc = $this->__itemdesc;
        $category = $this->__category;
        $size = $this->__size;
        $theo_weight = $this->__theoweight;
        $length = $this->__length;
        $length_total = $this->__lengthtotal;
        $sched = $this->__sched;
        $pcs_per_bundle = $this->__pcsperbundle;
        $bundles = $this->__bundles;
        $pieces = $this->__pieces;
        $net_tons = $this->__nettons;
        $m_tons = $this->__mtons;
        $cfr_lo_ft = $this->__cfrloft;
        $cfr_lo_mt = $this->__cfrlomt;
        $lot = $this->__lot;
        $unit_weight = $this->__unitweights;
        $extprice = $this->__extprice;
        $line = $this->__line;
        $upd = "UPDATE SO_Order_detail SET "
                . "  item ='$item'"
                . ", item_desc ='$item_desc'"
                . ", category ='$category'"
                . ", size ='$size'"
                . ", theo_weight ='$theo_weight'"
                . ", length ='$length'"
                . ", length_total ='$length_total'"
                . ", sched ='$sched'"
                . ", pcs_per_bundle ='$pcs_per_bundle'"
                . ", bundles ='$bundles'"
                . ", pieces ='$pieces'"
                . ", net_tons ='$net_tons'"
                . ", m_tons ='$m_tons'"
                . ", cfr_lo_mt ='$cfr_lo_ft'"
                . ", cfr_lo_ft ='$cfr_lo_mt'"
                . ", ext_price ='$extprice' "
                . ", lot ='$lot'"
                . ", unit_weight ='$unit_weight'  "
                . " WHERE doc_no = '$doc_no' AND line= $line ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->IsUpDel($this->StrConn, $upd);
        return $rs0;
    }

    function Upload() {
        $doc_no = $this->__doc_no;      
        $sl_co = $this->__sl_co;
        $sl_item = $this->__sl_item;
        $line = $this->__line ;
        $upd = "UPDATE SO_Order_detail SET "              
                . ", sl_co ='$sl_co' "
                . ", sl_item  ='$sl_item'"
                . " WHERE doc_no = '$doc_no' AND line= $line ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->IsUpDel($this->StrConn, $upd);
        return $rs0;
    }

    function Insert() {
        $cSql = new SqlSrv();
        $doc_no = $this->_doc_no;
        $item = $this->_item;
        $item_desc = $this->_item_desc;
        $size = $this->_size;
        $tw = $this->_theo_weight;
        $length = $this->_length;
        $length_total = $this->_length_total;
        $category = $this->_category;
        $sched = $this->_sched;
        $pcs_per_bundle = $this->_pcs_per_bundle;
        $bundles = $this->_bundles;
        $pieces = $this->_pieces;
        $net_tons = $this->_net_tons;
        $m_tons = $this->_m_tons;
        $cfr_lo_mt = $this->_cfr_lo_mt;
        $cfr_lo_ft = $this->_cfr_lo_ft;
        $ext_price = $this->_ext_price;
        $unitweights = $this->_unitweights;
        $lot = $this->_lot;
        $Rows = count($item);
        $x = 0;
        $z = $Rows;
        $result = 0;
        foreach ($item as $i => $r) {
            $x = $i + 1;
            $sql = "INSERT INTO SO_Order_detail "
                    . "("
                    . "doc_no,"
                    . "line,"
                    . "item,"
                    . "item_desc,"
                    . "category,"
                    . "size,"
                    . "theo_weight,"
                    . "length,"
                    . "length_total,"
                    . "sched,"
                    . "pcs_per_bundle,"
                    . "bundles,"
                    . "pieces,"
                    . "m_tons,"
                    . "cfr_lo_mt,"
                    . "cfr_lo_ft,"
                    . "ext_price,"
                    . "unit_weight,"
                    . "lot) "
                    . "VALUES "
                    . "("
                    . "'$doc_no',"
                    . "$x,"
                    . "'$item[$i]',"
                    . "'$item_desc[$i]',"
                    . "'$category[$i]',"
                    . "'$size[$i]',"
                    . "'$tw[$i]',"
                    . "'$length[$i]',"
                    . "'$length_total[$i]',"
                    . "'$sched[$i]',"
                    . "'$pcs_per_bundle[$i]',"
                    . "'$bundles[$i]',"
                    . "'$pieces[$i]',"
                    . "'$m_tons[$i]',"
                    . "'$cfr_lo_mt[$i]',"
                    . "'$cfr_lo_ft[$i]',"
                    . "'$ext_price[$i]',"
                    . "'$unitweights[$i]',"
                    . "'$lot[$i]')";
            $result = $result . $cSql->IsUpDel($this->StrConn, $sql);
        }


        return $result;
    }

//    function Update() {
//        $cSql = new SqlSrv();
//        $doc_no = $this->doc_no;
//        $tw = $this->theo_weight;
//        $length = $this->length;
//        $length_total = $this->length_total;
//        $pcs_per_bundle = $this->pcs_per_bundle;
//        $bundles = $this->bundles;
//        $pieces = $this->pieces;
//        $net_tons = $this->net_tons;
//        $m_tons = $this->m_tons;
//        $cfr_lo_mt = $this->cfr_lo_mt;
//        $cfr_lo_ft = $this->cfr_lo_ft;
//        $ext_price = $this->ext_price;
//
//        $Rows = count($item);
//        $x = 0;
//        $z = $Rows;
//        $result = 0;
//        foreach ($item as $i => $r) {
//            $x = $i + 1;
//            $sql = "UPDATE SO_Order_detail SET"
//                    . "item,"
//                    . "item_desc,"
//                    . "category,"
//                    . "size,"
//                    . "theo_weight,"
//                    . "length,"
//                    . "length_total,"
//                    . "sched,"
//                    . "pcs_per_bundle,"
//                    . "bundles,"
//                    . "pieces,"
//                    . "m_tons,"
//                    . "cfr_lo_mt,"
//                    . "cfr_lo_ft,"
//                    . "ext_price,"
//                    . "unit_weight,"
//                    . "lot) "
//                    . " WHERE doc_no ='$doc_no' and line= "
//                    . "("
//                    . ","
//                    . "$x,"
//                    . "'$item[$i]',"
//                    . "'$item_desc[$i]',"
//                    . "'$category[$i]',"
//                    . "'$size[$i]',"
//                    . "'$tw[$i]',"
//                    . "'$length[$i]',"
//                    . "'$length_total[$i]',"
//                    . "'$sched[$i]',"
//                    . "'$pcs_per_bundle[$i]',"
//                    . "'$bundles[$i]',"
//                    . "'$pieces[$i]',"
//                    . "'$m_tons[$i]',"
//                    . "'$cfr_lo_mt[$i]',"
//                    . "'$cfr_lo_ft[$i]',"
//                    . "'$ext_price[$i]',"
//                    . "'$unitweights[$i]',"
//                    . "'$lot[$i]')";
//            $result = $result . $cSql->IsUpDel($this->StrConn, $sql);
//        }
//
//
//        return $result;
//    }

    function GetLotByDocNo() {
        $doc_no = $this->_doc_no;
        $query = "SELECT lot  FROM SO_Order_detail WHERE doc_no =  '$doc_no' group by lot ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetRowsByDocNo() {
        $doc_no = $this->_doc_no;
        $query = "SELECT " . $this->AllFields . "  FROM SO_Order_detail WHERE doc_no =  '$doc_no' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

}
