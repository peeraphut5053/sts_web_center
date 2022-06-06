<?php

class QuantityMove {

    var $StrConn = "";
    public $_doc_no = "";
    public $_create_date = "";
    public $_create_by = "";
    public $_ArrQty = array();
    public $_ArrItem = array();
    public $_ArrFrom_loc = array();
    public $_ArrTo_loc = array();
    public $_ItemSearch = "";
    public $_Fromdate = "";
    public $_Todate = "";

    function setConn($c) {
        $this->StrConn = $c;
    }

    function GetListItem() {
        $ItemSearch = $this->_ItemSearch;
        $ItemSearchQuery = "";
        if ($ItemSearch) {
            $ItemSearchQuery = "and (it.item like '%$ItemSearch%' or iloc.loc like '%$ItemSearch%')";
        }
        $query = "select top 20 iloc.*, it.* from itemloc_mst iloc inner join item_mst it on iloc.item=it.item where qty_on_hand <> 0";
        $query = $query . $ItemSearchQuery;
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetListDoc() {
        $FromSearch = $this->_Fromdate;
        $ToSearch = $this->_Todate;
        $DateSearchQuery = "";
        if ($FromSearch != "" and $ToSearch != "") {
            $DateSearchQuery = "and (create_date between '$FromSearch' and '$ToSearch')";
        }

        $query = "select * FROM STS_qtymove_hdr ";
        $query = $query . $DateSearchQuery;
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetLocation() {
        $query = "SELECT loc,description FROM location_mst ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    Function GetDocumentDetail($doc_no) {
        $query = "select * FROM STS_qtymove where doc_no = '$doc_no'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    Function GetAllDocumentDetail() {
        $query = "select * FROM STS_qtymove ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    Function getLastDoc() {
        $query = "select top 1 doc_no FROM STS_qtymove_hdr order by doc_no desc ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    Function genDocNo($lastdoc) {
        $word = "MQ";
        $formatdate = date("Y") . date("m") . date("d");
        if (isset($lastdoc)) {
            $lastdoc = $lastdoc[0]["doc_no"];
        }
        if (isset($lastdoc)) {
            $run_num = substr($lastdoc, -4);
            $last_num = $run_num;
            $tmp_num = $last_num + 1;
            if ($tmp_num <= 9) {
                $run_num = "000" . $tmp_num;
            }
            if ($tmp_num > 9 && $tmp_num <= 99) {
                $run_num = "00" . $tmp_num;
            }
            if ($tmp_num > 99 && $tmp_num <= 999) {
                $run_num = "0" . $tmp_num;
            }
            if ($tmp_num > 999) {
                $run_num = $tmp_num;
            }
        } else {
            $run_num = "0001";
        }
        $codegen = $word . $formatdate . $run_num;
        return $codegen;
    }

    Function SaveData($lastdoc) {
        $result = "";
        //$doc_no = $this->_doc_no;
        $CallQuantityMove = new QuantityMove();
        $getdoc_no = $CallQuantityMove->genDocNo($lastdoc);
        $doc_no = $getdoc_no;
        $create_date = $this->_create_date;
        $create_by = $this->_create_by;
        $ArrQty = $this->_ArrQty;
        $Arritem = $this->_ArrItem;
        $FromLoc = $this->_ArrFrom_loc;
        $ToLoc = $this->_ArrTo_loc;

        $ToLoc_len = count($ToLoc);
        $queryhdr = "INSERT INTO STS_qtymove_hdr("
                . "doc_no,create_date,create_by"
                . ")VALUES("
                . "?,?,?) ";
        $paramshdr = array(
            $doc_no,
            $create_date,
            $create_by,
        );
        $stmthdr = sqlsrv_prepare($this->StrConn, $queryhdr, $paramshdr);
        if (sqlsrv_execute($stmthdr) == false) {
            $result = sqlsrv_errors();
        } else {
            $result = "1";
        }

        for ($i = 0; $i < $ToLoc_len; $i++) {
            $query = "INSERT INTO STS_qtymove("
                    . "doc_no,"
                    . "type,"
                    . "date,"
                    . "qty,"
                    . "item,"
                    . "FormWhse,"
                    . "FromLoc,"
                    . "FromLot,"
                    . "ToWhse,"
                    . "ToLoc,"
                    . "ToLot,"
                    . "ZeroCost,"
                    . "TransNum,"
                    . "RsvdNum,"
                    . "Stat,"
                    . "RefNum,"
                    . "RefLineSuf,"
                    . "RefRelease,"
                    . "Refstr,"
                    . "UnitCost,"
                    . "MatCost,"
                    . "LbrCost,"
                    . "FovhdCost,"
                    . "VovhdCost,"
                    . "OutCost,"
                    . "Infobar,"
                    . "DocumentNum,"
                    . "FromImportDocId,"
                    . "ToImportDocId,"
                    . "ReasonCode,"
                    . "EmpNum,"
                    . "FromSiteRecordDate"
                    . ")VALUES("
                    . "?,?,?,?,?,?,?,?,?,?,"
                    . "?,?,?,?,?,?,?,?,?,?,"
                    . "?,?,?,?,?,?,?,?,?,?,?,?) ";
            $params = array(
                $doc_no,
                "M",
                $create_date,
                $ArrQty[$i],
                $Arritem[$i],
                "MAIN",
                "$FromLoc[$i]",
                "[FromLot]",
                "[ToWhse]",
                $ToLoc[$i],
                "[ToLot]",
                "[ZeroCost]",
                "[TransNum]",
                "[RsvdNum]",
                "[Stat]",
                "[RefNum]",
                "[RefLineSuf]",
                "[RefRelease]",
                "[Refstr]",
                "[UnitCost]",
                "[MatCost]",
                "[LbrCost]",
                "[FovhdCost]",
                "[VovhdCost]",
                "[OutCost]",
                "[Infobar]",
                "[DocumentNum]",
                "[FromImportDocId]",
                "[ToImportDocId]",
                "[ReasonCode]",
                "[EmpNum]",
                "[FromSiteRecordDate]",
            );
            $stmt = sqlsrv_prepare($this->StrConn, $query, $params);
            if (sqlsrv_execute($stmt) == false) {
                $result = sqlsrv_errors();
            } else {
                $result = "1";
            }
        }
        return $result;
    }

}
