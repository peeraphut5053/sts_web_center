<?php

class Mv_Bc_Tag {

    var $StrConn = "";
    public $_site_ref = "";
    public $id = "";
    public $barcode = "";
    public $uf_grade = "";
    public $uf_tickness = "";
    public $qty1 = "";
    public $um1 = "";
    public $uf_coil_no = "";
//     public $grn_num = "";
//      public $uf_coil_no = "";
    public $mfg_date = "";
    public $uf_width = "";
    public $uf_name = "";
    public $lot = "";
    public $item = "";
    public $po_num = "";
    public $u_m = "";
    public $doc_num = "";

    function setConn($c) {
        $this->StrConn = $c;
    }

    function GetProperties($tag_id) {
        $query = "SELECT doc_num,id,uf_grade,qty1,um1,uf_coil_no,mfg_date,uf_Tickness,uf_width,uf_name,lot,item,po_num, sts_no FROM Mv_Bc_Tag WHERE id = '$tag_id' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);

        $this->barcode = $tag_id;
        $this->uf_grade = $rs0[1]["uf_grade"];
        $this->qty1 = $rs0[1]["qty1"];
        $this->um1 = $rs0[1]["um1"];
        $this->uf_coil_no = $rs0[1]["uf_coil_no"];
        $this->uf_tickness = $rs0[1]["uf_Tickness"];
        $this->mfg_date = $rs0[1]["mfg_date"]->format("Y-m-d");
        $this->uf_width = $rs0[1]["uf_width"];
        $this->uf_name = $rs0[1]["uf_name"];
        $this->lot = $rs0[1]["lot"];
        $this->item = $rs0[1]["item"];
        $this->doc_num = $rs0[1]["sts_no"];
        $this->po_num = $rs0[1]["po_num"];
        return $rs0;
    }

//    function UpdateGrnLineByTagPrint($grn_num, $grn_line) {
//        $query = "UPDATE grn_line_mst SET Uf_tag_status = 1  WHERE grn_num = '$grn_num' AND grn_line = $grn_line ";
//        $cSql = new SqlSrv();
//        $rs0 = $cSql->IsUpDel($this->StrConn, $query);
//        echo $query;
//        return $rs0;
//    }
}
