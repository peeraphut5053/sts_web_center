<?php

class po_qc_sl {

    var $StrConn = "";
    public $_start_Jobdate = ""; //
    public $_end_Jobdate = ""; //
    public $_start_date = ""; //
    public $_end_date = ""; //
    public $_lot = ""; //
    public $_item = ""; //
    public $_end_Lot = ""; //
    public $_ref_num = ""; //
    public $_trans_type = ""; //
    public $_job_type = ""; //
    public $_u_m = ""; //
    public $_w_c = ""; //

    function setConn($c) {
        $this->StrConn = $c;
    }

    function InsertSts_po_qc($po_QC) {
        for ($countpo_QC = 0; $countpo_QC < count($po_QC); $countpo_QC++) {
            $array_col = array_keys($po_QC[$countpo_QC]);
            $insert_col_query = "";
            for ($i = 0; $i < count($array_col); $i++) {
                $column_name = $array_col[$i];
                ($column_name == "user") ? $column_name = "users" : $column_name = $column_name;
                $insert_col_query = $insert_col_query . $column_name;
                if ($i < count($array_col) - 1) {
                    $insert_col_query = $insert_col_query . ",";
                }
            }
            $array_val = array_values($po_QC[$countpo_QC]);
            $insert_val_query = "";
            for ($i = 0; $i < count($array_val); $i++) {
                $column_val = $array_val[$i];
                $insert_val_query = $insert_val_query . "'" . $column_val . "'";
                if ($i < count($array_val) - 1) {
                    $insert_val_query = $insert_val_query . ",";
                }
            }

            $sno = $po_QC[$countpo_QC]["sno"];
            $querychk = "  select * FROM STS_po_qc where sno = '$sno' ";
            $cSqlchk = new SqlSrv();
            $rs0 = $cSqlchk->SqlQuery($this->StrConn, $querychk);
            array_splice($rs0, count($rs0) - 1, 1);
            $sno2 = "";
            (isset($rs0[0]["sno"])) ? $sno2 = $rs0[0]["sno"] : $sno2 = "";
            if ($sno != $sno2) {
                $deletetemp_query = "DELETE FROM STS_po_qc";
                $cSqldelete = new SqlSrv();
                $cSqldelete->SqlQuery($this->StrConn, $deletetemp_query);
                
                $insert_query = "INSERT INTO STS_po_qc(" . $insert_col_query . ") VALUES (" . $insert_val_query . ") ";
                $cSql = new SqlSrv();
                $cSql->SqlQuery($this->StrConn, $insert_query);
                echo $insert_query;
            }
        }
    }

    Function updateRemark($tag_id, $remark) {
        $result = "";
        $query = "Update STS_QC_ISSUE_img_upload Set "
                . " remark = '$remark' where tag_id = '$tag_id' ";
        $stmt = sqlsrv_prepare($this->StrConn, $query);
        if (sqlsrv_execute($stmt) == false) {
            $result = sqlsrv_errors();
        } else {
            $result = "1";
        }
        return $this->checkTagImgDuplicate($tag_id);
    }

    Function updateImg($tag_id, $file_name_x, $file_name) {
        $result = "";
        $query = "Update STS_QC_ISSUE_img_upload Set "
                . " $file_name_x = '$file_name' where tag_id = '$tag_id' ";
        $stmt = sqlsrv_prepare($this->StrConn, $query);
        if (sqlsrv_execute($stmt) == false) {
            $result = sqlsrv_errors();
        } else {
            $result = "1";
        }
        return $result;
    }
    
    Function DeleteImg($tag_id, $file_name_x, $file_name) {
        $result = "";
        $query = "Update STS_QC_ISSUE_img_upload Set "
                . " $file_name_x = '' where tag_id = '$tag_id' ";
        $stmt = sqlsrv_prepare($this->StrConn, $query);
        if (sqlsrv_execute($stmt) == false) {
            $result = sqlsrv_errors();
        } else {
            $result = "1";
        }
        return $result;
    }
    Function DeleteTagId($tag_id) {
        $result = "";
        $query = "Delete STS_QC_ISSUE_img_upload "
                . " where tag_id = '$tag_id' ";
        $stmt = sqlsrv_prepare($this->StrConn, $query);
        if (sqlsrv_execute($stmt) == false) {
            $result = sqlsrv_errors();
        } else {
            $result = "1";
        }
        return $result;
    }

    Function checkTagImgDuplicate($tag_id) {
        $cSql = new SqlSrv();
        $query = "select * FROM STS_QC_ISSUE_img_upload where tag_id = '$tag_id' ";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        (isset($rs[1]["tag_id"])) ? $result = "1" : $result = "0";
        return $result;
    }

    Function SearchTagDetail($tag_id, $create_by) {
        if ($tag_id != '') {
            $cSql = new SqlSrv();
            if ($this->checkTagImgDuplicate($tag_id) == "0") {
                $query0 = " select lot,Uf_NPS,Uf_Schedule,Uf_length,sts_no FROM MV_BC_TAG "
                        . " left join item_mst ON MV_BC_TAG.item = item_mst.item "
                        . " where id = '$tag_id' ";
                $rs0 = $cSql->SqlQuery($this->StrConn, $query0);
                array_splice($rs0, count($rs0) - 1, 1);
                $lot = $rs0[0]['lot'];
                $Uf_NPS = $rs0[0]['Uf_NPS'];
                $Uf_Schedule = $rs0[0]['Uf_Schedule'];
                $Uf_length = $rs0[0]['Uf_length'];
                $sts_no = $rs0[0]['sts_no'];
                $create_date = date("Y-m-d H:i:s");
                $queryInsert = "INSERT INTO STS_QC_ISSUE_img_upload("
                        . "tag_id, lot, file_name_1, file_name_2, file_name_3, file_name_4, file_name_5,"
                        . " create_date, create_by, remark,Uf_NPS,Uf_Schedule,Uf_length,sts_no"
                        . ")VALUES('$tag_id','$lot','','','','','',"
                        . " '$create_date','$create_by','','$Uf_NPS','$Uf_Schedule','$Uf_length','$sts_no') ";
                $cSql->SqlQuery($this->StrConn, $queryInsert);
            }
            $query = "select * FROM STS_QC_ISSUE_img_upload  where tag_id = '$tag_id' ";
            $rs = $cSql->SqlQuery($this->StrConn, $query);
            array_splice($rs, count($rs) - 1, 1);
        } else {
            $rs = 0;
        }
        return $rs;
    }

    function GetImgTagUpload($FromTagId, $size_list, $sts_no) {
        $query = " select id,tag_id,lot,file_name_1,file_name_2,file_name_3,file_name_4,file_name_5,"
                . " create_date,create_by,remark,"
                . " isnull(Uf_NPS,'') Uf_NPS,"
                . " isnull(Uf_Schedule,'') Uf_Schedule,"
                . " isnull(Uf_length,'') Uf_length,"
                . " isnull(sts_no,'') sts_no,"
                . " CONCAT(Uf_NPS,Uf_Schedule,Uf_length) size "
                . " FROM STS_QC_ISSUE_img_upload where 1=1 "
                . " and "
                . " ( tag_id like '%$FromTagId%' and  "
                . " CONCAT(REPLACE(Uf_NPS,'/\s+/g',''),Uf_Schedule,Uf_length) like '%$size_list%' ";
        if ($sts_no != '') {
            $query = $query . "and sts_no like '%$sts_no%' ";
        }
        $query = $query . " )";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

}
