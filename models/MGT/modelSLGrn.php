<?php

class SLGRN {

    var $StrConn = "";
    public $_site_ref = "";
    public $_vend_num = "";
    public $_grn_num = "";
    public $_grn_line = "";
    public $_po_num = "";
    public $_po_line = "";
    public $_po_release = "";
    public $_lot = "";
    public $_trans = "";
    public $_tag_status = "";
    public $_container = 0;
    public $_qty_shipped_conv = 0;
    public $_u_m = 0;
    public $_po_date = "";

    function setConn($c) {
        $this->StrConn = $c;
    }

    function CreateGrnHdr() {
        $site_ref = $this->_site_ref;
        $vend_num = $this->_vend_num;
        $grn_num = $this->_grn_num;
        $whse = "MAIN";
        $grnHdrDate = date("Y-m0d H:i:s");
        $q = "INSERT INTO grn_hdr_mst (site_ref, vend_num , grn_num , grn_hdr_date , stat , edi_asn , whse )"
                . " VALUES ('$site_ref','$vend_num','$grn_num','$grnHdrDate','I',0,'$whse') ";
        $cSql = new SqlSrv();
        $cSql->IsUpDel($this->StrConn, $q);
    }

    function Ajax_GetRows_Limit($limit) {
        $query = "SELECT TOP $limit "
                . "vend_num,grn_num,grn_line,po_num,po_line,po_release,container,qty_shipped_conv,u_m,"
                . "qty_rec,qty_rej,qty_vouchered,NoteExistsFlag,RecordDate,RowPointer,qty_returned,CreatedBy,"
                . "UpdatedBy,CreateDate,InWorkflow,uf_grade,uf_manu_by,uf_qty_rec2,uf_qty_ship2,uf_supplier "
                . "FROM grn_line_mst ORDER BY RecordDate DESC";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function Ajax_GetRows_Limit_Cond($limit, $sWhere) {
        $query = "SELECT TOP $limit "
                . "vend_num,grn_num,grn_line,po_num,po_line,po_release,container,qty_shipped_conv,u_m,"
                . "qty_rec,qty_rej,qty_vouchered,NoteExistsFlag,RecordDate,RowPointer,qty_returned,CreatedBy,"
                . "UpdatedBy,CreateDate,InWorkflow,uf_grade,uf_manu_by,uf_qty_rec2,uf_qty_ship2,uf_supplier "
                . "FROM grn_line "
                . "$sWhere";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
  function SearchLine($sWhere) {
        $query = "SELECT vend_num,grn_num,grn_line,po_num,po_line,po_release,container,qty_shipped_conv,u_m,"
                . "qty_rec,qty_rej,qty_vouchered,NoteExistsFlag,RecordDate,RowPointer,qty_returned,CreatedBy,"
                . "UpdatedBy,CreateDate,InWorkflow,uf_grade,uf_manu_by,uf_qty_rec2,uf_qty_ship2,uf_supplier "
                . "FROM grn_line "
                . "$sWhere";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
    function UpdateGrnLineByTagPrint($grn_num, $grn_line) {
        $query = "UPDATE grn_line_mst SET Uf_tag_status = '1'  WHERE grn_num = '$grn_num' AND grn_line = $grn_line ";
        $cSql = new SqlSrv();
        $rs0 = 0;
        $CloseTriggerUpResult = 0;
        $OpenTriggerUpResult = 0;
        //\\///\\///// DISABLED TRIGGER \\\/\//\\\\/////\\\
        $CloseTriggerUpResult = $cSql->IsUpDel($this->StrConn, "DISABLE Trigger grn_line_mst.grn_line_mstIup ON grn_line_mst ");
        if ($CloseTriggerUpResult == 1) {
            $rs0 = $cSql->IsUpDel($this->StrConn, $query);
        }
        if ($rs0 == 1) {
            //\\///\\//\//// ENABLE TRIGGER \\\/\//\\\\/////\\\
            $OpenTriggerUpResult = $cSql->IsUpDel($this->StrConn, "ENABLE Trigger grn_line_mst.grn_line_mstIup ON grn_line_mst ");
        }

        return $CloseTriggerUpResult . "-" . $rs0 . "-" . $OpenTriggerUpResult;
    }

}
