<?php

class img_upload {

    var $StrConn = "";

    function setConn($c) {
        $this->StrConn = $c;
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
                $queryInsert = "INSERT INTO Mv_Bc_Tag_img_upload("
                        . "tag_id, lot, file_name_1, file_name_2,"
                        . " create_date, create_by, remark,sts_no"
                        . ")VALUES('$tag_id','$lot','','',"
                        . " '$create_date','$create_by','','$sts_no') ";
                $cSql->SqlQuery($this->StrConn, $queryInsert);
            }
            $query = "select * FROM Mv_Bc_Tag_img_upload  where tag_id = '$tag_id' ";
            $rs = $cSql->SqlQuery($this->StrConn, $query);
            array_splice($rs, count($rs) - 1, 1);
        } else {
            $rs = 0;
        }
        return $rs;
    }

    Function updateRemark($tag_id, $remark) {
        $result = "";
        $query = "Update Mv_Bc_Tag_img_upload Set "
                . " remark = '$remark' where tag_id = '$tag_id' ";
        $stmt = sqlsrv_prepare($this->StrConn, $query);
        if (sqlsrv_execute($stmt) == false) {
            $result = sqlsrv_errors();
        } else {
            $result = "1";
        }
        return $this->checkTagImgDuplicate($tag_id);
    }

    Function updateImg($tag_id, $file_name_x, $file_name, $create_by) {
        $result = "";
        $query = "Update Mv_Bc_Tag_img_upload Set "
                . " $file_name_x = '$file_name', create_by = '$create_by' where tag_id = '$tag_id' ";
        $stmt = sqlsrv_prepare($this->StrConn, $query);
        if (sqlsrv_execute($stmt) == false) {
            $result = sqlsrv_errors();
        } else {
            $result = "1";
        }
        return $query;
    }

    Function DeleteImg($tag_id, $file_name_x, $file_name) {
        $result = "";
        $query = "Update Mv_Bc_Tag_img_upload Set "
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
        $query = "Delete Mv_Bc_Tag_img_upload "
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
        $query = "select * FROM Mv_Bc_Tag_img_upload where tag_id = '$tag_id' ";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        (isset($rs[1]["tag_id"])) ? $result = "1" : $result = "0";
        return $result;
    }

    function GetImgTagUpload($FromTagId, $size_list, $sts_no,$job) {
        $query = "select Mv_Bc_Tag.item,Mv_Bc_Tag.id as tag_id, isnull(job_mst.Uf_refno,co_mst.Uf_StsPO_refNo) STSPO,"
                . " item_mst.Uf_TypeEnd, isnull(item_mst.Uf_spec,item_mst.Uf_class) Uf_spec, item_mst.Uf_NPS,"
                . " item_mst.Uf_pack, Mv_Bc_Tag.uf_pack,"
                . " isnull(Mv_Bc_Tag.sts_no,'') sts_no,"
                . " Mv_Bc_Tag.sts_no2, Mv_Bc_Tag.sts_no3,"
                . " Mv_Bc_Tag.qty_sts_no, Mv_Bc_Tag.qty_sts_no2, Mv_Bc_Tag.qty_sts_no3,"
                . " isnull(custaddr_mst.name,'') name,"
                . " isnull(co_mst.cust_po,'') cust_po, "
                . " isnull(custaddr_mst.city,'') city,"
                . " isnull( Mv_Bc_Tag.lot ,'') lot,"
                . " Mv_Bc_Tag_img_upload.file_name_1,"
                . " Mv_Bc_Tag_img_upload.file_name_2, "
                . " Mv_Bc_Tag_img_upload.create_by, "
                . " Mv_Bc_Tag_img_upload.remark "
                . " FROM Mv_Bc_Tag_img_upload "
                . " LEFT JOIN Mv_Bc_Tag ON Mv_Bc_Tag.id = Mv_Bc_Tag_img_upload.tag_id "
                . " LEFT JOIN item_mst ON item_mst.item = Mv_Bc_Tag.item "
                . " LEFT JOIN job_mst ON Mv_Bc_Tag.job = job_mst.job "
                . " LEFT JOIN co_mst ON co_mst.co_num = job_mst.ord_num "
                . " LEFT JOIN custaddr_mst ON custaddr_mst.cust_num = co_mst.cust_num and custaddr_mst.cust_seq = co_mst.cust_seq where 1=1 ";

//        $query = " SELECT * FROM Mv_Bc_Tag_img_upload"
//                . " LEFT JOIN Mv_Bc_Tag ON Mv_Bc_Tag.id = Mv_Bc_Tag_img_upload.tag_id  where 1=1 ";

        if ($FromTagId != "") {
            $query = $query . "and Mv_Bc_Tag_img_upload.tag_id = '$FromTagId' ";
        }
        if ($job != "") {
            $query = $query . "and Mv_Bc_Tag_img_upload.lot like '%$job%' ";
        }
        ////        $query = " select id,tag_id,lot,file_name_1,file_name_2,"
//                . " create_date,create_by,remark,"
//                . " isnull(sts_no,'') sts_no"
//                . " FROM Mv_Bc_Tag_img_upload LEFT JOIN ON Mv_Bc_Tag_img_upload.tag_id = MV_BC_TAG. where 1=1 "
//                . " and "
//                . " ( tag_id like '%$FromTagId%' ";
//        if ($sts_no != '') {
//            $query = $query . "and sts_no like '%$sts_no%' ";
//        }
//        $query = $query . " )";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    Function GetRowsImgUploadDoHeader() {
        $cSql = new SqlSrv();
        $query = "SELECT * FROM Mv_Bc_Tag_img_upload_DoHeader  WHERE 1=1 ";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetDOHeaderForCreateDoc($DoHeader_id) {
        $query = "select AST_ship.do_num , AST_ship.co_num , count(distinct(AST_ship.co_line)) as countCO, co_mst.cust_po as CUST_PO , ISNULL(co_mst.Uf_StsPO_refNo,'') as STS_PO,file_name_1,file_name_2 from AST_ship left join co_mst on AST_ship.co_num = co_mst.co_num left join Mv_Bc_Tag_img_upload_DoHeader on AST_ship.do_num = Mv_Bc_Tag_img_upload_DoHeader.DoHeader_id where do_num = '$DoHeader_id' group by AST_ship.do_num,AST_ship.co_num ,co_mst.cust_po ,co_mst.Uf_StsPO_refNo,file_name_1,file_name_2 ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetDOListForCreateDoc($DoHeader_id) {
        $query = "select distinct top 1001 isnull(AST_ship.tag_id,MV_Bc_Tag.id) as id,"
                . " do_num ,AST_ship.co_num ,co_line  ,co_mst.cust_po as CUST_PO ,"
                . "ISNULL(co_mst.Uf_StsPO_refNo,'') as STS_PO  ,AST_ship.item ,qty ,"
                . "AST_ship.lot ,loc ,MV_BC_TAG.sts_no ,qty_sts_no ,sts_no2 ,qty_sts_no2,sts_no3 ,"
                . "qty_sts_no3,item_mst.Uf_typeEnd,item_mst.Uf_NPS,item_mst.Uf_Grade,item_mst.Uf_Schedule,item_mst.Uf_length,item_mst.Uf_spec,Mv_Bc_Tag_img_upload.file_name_1,Mv_Bc_Tag_img_upload.file_name_2  from AST_ship left join mv_bc_tag on AST_ship.lot = mv_bc_tag.lot left join co_mst  on AST_ship.co_num = co_mst.co_num left join item_mst on item_mst.item = AST_ship.item left join Mv_Bc_Tag_img_upload on AST_ship.tag_id = Mv_Bc_Tag_img_upload.tag_id where do_num like '%$DoHeader_id%'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    Function uploadImgDoHeader($DoHeader_id, $file_name_1, $file_name_2) {
        $result = "";
        $query = "INSERT INTO Mv_Bc_Tag_img_upload_DoHeader("
                . "DoHeader_id, file_name_1, file_name_2, create_date"
                . ")VALUES(?,?,?,?) ";
        $params = array($DoHeader_id, $file_name_1, $file_name_2, "");
        $stmt = sqlsrv_prepare($this->StrConn, $query, $params);
        if (sqlsrv_execute($stmt) == false) {
            $result = sqlsrv_errors();
        } else {
            $result = "1";
        }
        return "insert success";
    }

}
