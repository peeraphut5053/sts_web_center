<?php

class BcTag {

    var $StrConn = "";
    public $_StartDate = "";
    public $_EndDate = "";
    public $_docs = array();

    function setConn($c) {
        $this->StrConn = $c;
    }

    Function GetRows() {
        $cSql = new SqlSrv();
        $query = "SELECT * FROM Mv_Bc_Tag_img_upload  WHERE 1=1 ";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function GetRowsImgUploadDoHeader() {
        $cSql = new SqlSrv();
        $query = "SELECT * FROM Mv_Bc_Tag_img_upload_DoHeader  WHERE 1=1 ";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function SearchTagDetail($tag_id) {
        $cSql = new SqlSrv();
        $query = "select lot_loc_mst.loc,Mv_Bc_Tag.item,itemwhse_mst.qty_on_hand,item_mst.description,item_mst.u_m,Mv_Bc_Tag.id, isnull(job_mst.Uf_refno,co_mst.Uf_StsPO_refNo) STSPO, item_mst.Uf_TypeEnd, isnull(item_mst.Uf_spec,item_mst.Uf_class) Uf_spec, item_mst.Uf_NPS, item_mst.Uf_pack, Mv_Bc_Tag.uf_pack, Mv_Bc_Tag.sts_no, Mv_Bc_Tag.sts_no2, Mv_Bc_Tag.sts_no3, Mv_Bc_Tag.qty_sts_no, Mv_Bc_Tag.qty_sts_no2, Mv_Bc_Tag.qty_sts_no3, custaddr_mst.name, co_mst.cust_po, custaddr_mst.city, Mv_Bc_Tag.lot,Mv_Bc_Tag.uf_act_Weight as unit_weight FROM Mv_Bc_Tag LEFT JOIN item_mst ON item_mst.item = Mv_Bc_Tag.item LEFT JOIN job_mst ON Mv_Bc_Tag.job = job_mst.job LEFT JOIN co_mst ON co_mst.co_num = job_mst.ord_num LEFT JOIN custaddr_mst ON custaddr_mst.cust_num = co_mst.cust_num and custaddr_mst.cust_seq = co_mst.cust_seq "
                . " LEFT JOIN itemwhse_mst ON itemwhse_mst.item = item_mst.item LEFT JOIN lot_loc_mst ON lot_loc_mst.lot = Mv_Bc_Tag.lot   where 1=1 ";
        $query = $query . "and id = '$tag_id' ";

        $query = "select mv_bc_tag.id, mv_bc_tag.lot, loc, mv_bc_tag.item, qty1, item_mst.u_m FROM lot_loc_mst"
                . " LEFT JOIN mv_bc_tag ON lot_loc_mst.lot = mv_bc_tag.lot"
                . " LEFT JOIN item_mst ON item_mst.item = Mv_Bc_Tag.item"
                . " where mv_bc_tag.id = '$tag_id' and qty1 = qty_on_hand";

        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function SearchTagDetailCheckByDO($tag_id, $do_num) {
        $cSql = new SqlSrv();
        $do_num_arr = explode(",", $do_num);
        $do_num_list = "";
        for ($i = 0; $i < count($do_num_arr); $i++) {
            $do_num_list = $do_num_list . "'" . $do_num_arr[$i] . "'";
            if ($i < count($do_num_arr) - 1) {
                $do_num_list = $do_num_list . ",";
            }
        }
        $query = "select distinct tag.id, tag.job, tag.lot, tag.item, tag.qty1, preship.do_num, preship.do_line
  , preship.co_num --, preship.co_line 
from mv_bc_tag tag 
  inner join job_mst on tag.job = job_mst.job and tag.item=job_mst.item
  left join AIT_Preship_Do_Seq preship on job_mst.ord_num = preship.co_num 
    --and job_mst.ord_line = preship.co_line 
where tag.job is not null and job_mst.item = tag.item and tag.id = '$tag_id' and preship.do_num in ( " . $do_num_list . " ) ";
//        echo $query;
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }
    
    Function SelectDOList($do_num) {
        $cSql = new SqlSrv();
        $do_num_arr = explode(",", $do_num);
        $do_num_list = "";
        for ($i = 0; $i < count($do_num_arr); $i++) {
            $do_num_list = $do_num_list . "'" . $do_num_arr[$i] . "'";
            if ($i < count($do_num_arr) - 1) {
                $do_num_list = $do_num_list . ",";
            }
        }
        $query = "select distinct tag.id, tag.job, tag.lot, tag.item, tag.qty1, preship.do_num, preship.do_line, preship.co_num, preship.co_line from mv_bc_tag tag left join job_mst on tag.job = job_mst.job left join AIT_Preship_Do_Seq preship on job_mst.ord_num = preship.co_num and job_mst.ord_line = preship.co_line "
                . "where tag.job is not null and preship.do_num in ( " . $do_num_list . " ) ";
//        echo $query;
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function V_WebApp_BarcodePrint($tag_id) {
        $cSql = new SqlSrv();
        $query = "select * FROM V_WebApp_BarcodePrint where id = '$tag_id'";

        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function location_mst() {
        $cSql = new SqlSrv();
        $query = "select * FROM location_mst ";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function wc_mst() {
        $cSql = new SqlSrv();
        $query = "select * FROM wc_mst ";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function item_mst() {
        $cSql = new SqlSrv();
        $query = "select item FROM lot_loc_mst where qty_on_hand > 0  group by item";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function STS_qty_move_hrd() {
        $cSql = new SqlSrv();
        $query = "select top 3500 doc_num, loc, create_date, doc_type, destination FROM STS_qty_move_hrd order by doc_num desc ";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function STS_qty_move_line($doc_num) {
        $cSql = new SqlSrv();
        $query = "select STS_qty_move_line.id,fromloc as loc,mv_bc_tag.id
        , CONVERT(int, mv_bc_tag.qty1) as qty1
        , STS_qty_move_line.lot
  ,lot_mst.item
        ,item_mst.u_m,STS_qty_move_line.boat_position 
   , case when HRD.doc_type <> 'Truck' then
   case when mv_bc_tag.ship_stat = 1 then 'shipped' 
              when STS_qty_move_line.fromloc = lot_loc_mst.loc then ''
              else lot_loc_mst.loc end 
 else case when STS_qty_move_line.fromloc = lot_loc_mst.loc or lot_loc_mst.loc is null then '' else lot_loc_mst.loc end    
   end  as current_loc
FROM STS_qty_move_line 
   LEFT JOIN lot_mst ON lot_mst.lot = STS_qty_move_line.lot 
   LEFT JOIN mv_bc_tag ON lot_mst.lot = mv_bc_tag.lot and lot_mst.item = mv_bc_tag.item 
   and case when STS_qty_move_line.tag_id is null then '1' else mv_bc_tag.id end =  case when STS_qty_move_line.tag_id is null then '1' else STS_qty_move_line.tag_id end 
   LEFT JOIN lot_loc_mst ON lot_loc_mst.lot = STS_qty_move_line.lot and lot_loc_mst.item = mv_bc_tag.item and lot_loc_mst.qty_on_hand > 0 --and STS_qty_move_line.toloc = lot_loc_mst.loc 
   LEFT JOIN item_mst ON item_mst.item = mv_bc_tag.item 
INNER JOIN STS_qty_move_hrd HRD on hrd.doc_num = STS_qty_move_line.doc_num 
where STS_qty_move_line.doc_num = '$doc_num' and mv_bc_tag.active=1 and mv_bc_tag.qty1 <> 0";
        // left join lot_loc_mst lot on lot.lot = STS_qty_move_line .lot and lot.qty_on_hand > 0
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function update_doc_hdr() {

        $lastNum = 1000;
        $idNum = 1148;
        for ($i = 0; $i < 880; $i++) {
            $lastNum = $lastNum + 1;
            $idNum = $idNum + 1;
            $query = "update STS_qty_move_hrd set doc_num = 'MQD2007$lastNum' where id = $idNum";
            echo $query . "<br>";
        }


        //return $rs;
    }

    // Function GEN_Doc_num() {
    //     $cSql = new SqlSrv();
    //     $query = "EXEC [dbo].[STS_QtyMoveLotLocation_GEN_Doc_num] 
    //               @newDocumentNumber = OUTPUT";
    //     $cSql->SqlQuery($this->StrConn, $query);

    //     $query2 = "select top (1) doc_num FROM STS_qty_move_doc_num order by id desc";
    //     $rs2 = $cSql->SqlQuery($this->StrConn, $query2);
    //     array_splice($rs2, count($rs2) - 1, 1);
    //     return $rs2[0];

    // }

    // Function moveqty_create_hdr($toLoc, $w_c, $doc_type, $do_num, $boatList,$destination,$ActWeight,$newdo_num) {
    //     if ($doc_type == "") {
    //         $doc_type == "Internal";
    //     }
    //     $cSql = new SqlSrv();
    //     $query = "exec STS_QtyMoveLotLocation_GEN_HEADER_NEW @loc = '$toLoc' , @w_c= '$w_c' ,@doc_type= '$doc_type' , @do_num='$do_num',@boatList='$boatList',@destination = '$destination',@ActWeight = '$ActWeight' ,@newDocumentNumber ='$newdo_num'  ";
    //     $cSql->SqlQuery($this->StrConn, $query);

    //     $query2 = " select top (1)* FROM STS_qty_move_hrd order by id desc";
    //     $rs2 = $cSql->SqlQuery($this->StrConn, $query2);
    //     array_splice($rs2, count($rs2) - 1, 1);
    //     return $rs2[0];
    // }

    // Function moveqty_create_line($docnum, $docline, $tagnum, $toLoc, $boatPosition) {
    //     $cSql = new SqlSrv();
    //     $query = "exec STS_QtyMoveLotLocation @docnum=N'$docnum', @docline=N'$docline', @tagNum = N'$tagnum' ,@toLoc = N'$toLoc', @boatPosition =  '$boatPosition' ";
    //     $rs = $cSql->SqlQuery($this->StrConn, $query);
    //     return $rs[0];
    // }

    Function checkTagImgDuplicate($tag_id) {
        $cSql = new SqlSrv();
        $query = "select * FROM Mv_Bc_Tag_img_upload"
                . " where 1=1 ";
        $query = $query . "and tag_id = '$tag_id' ";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        (isset($rs[1]["tag_id"])) ? $result = "1" : $result = "0";
        return $result;
    }

    Function checkItemLotLoc($loc) {
        $cSql = new SqlSrv();
        $query2 = " select lot_loc_mst.item,item_mst.description,loc,Uf_pack,CONVERT(INT, sum(qty_on_hand)) sumqty,count(lot) as countlot FROM lot_loc_mst LEFT JOIN item_mst on item_mst.item = lot_loc_mst.item "
                . " where loc = '$loc' and qty_on_hand <> 0 group by lot_loc_mst.item,item_mst.description,loc,Uf_pack ";
        $rs2 = $cSql->SqlQuery($this->StrConn, $query2);
        array_splice($rs2, count($rs2) - 1, 1);
        return $rs2;
    }

    Function uploadImg($tag_id, $lot, $file_name_1, $file_name_2) {
        $result = "";
        if ($this->checkTagImgDuplicate($tag_id) == "0") {
            $query = "INSERT INTO Mv_Bc_Tag_img_upload("
                    . "tag_id, lot, file_name_1, file_name_2, create_date"
                    . ")VALUES(?,?,?,?,?) ";
            $params = array($tag_id, $lot, $file_name_1, $file_name_2, "");
            $stmt = sqlsrv_prepare($this->StrConn, $query, $params);
            if (sqlsrv_execute($stmt) == false) {
                $result = sqlsrv_errors();
            } else {
                $result = "1";
            }
        } else {
            
        }

        return "insert success";
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

    function GetImgTagUpload($lot, $FromTagId, $ToTagId) {
        $query = "select Mv_Bc_Tag.item,Mv_Bc_Tag.id , isnull(job_mst.Uf_refno,co_mst.Uf_StsPO_refNo) STSPO,"
                . " item_mst.Uf_TypeEnd, isnull(item_mst.Uf_spec,item_mst.Uf_class) Uf_spec, item_mst.Uf_NPS,"
                . " item_mst.Uf_pack, Mv_Bc_Tag.uf_pack, Mv_Bc_Tag.sts_no, Mv_Bc_Tag.sts_no2, Mv_Bc_Tag.sts_no3,"
                . " Mv_Bc_Tag.qty_sts_no, Mv_Bc_Tag.qty_sts_no2, Mv_Bc_Tag.qty_sts_no3, custaddr_mst.name,"
                . " co_mst.cust_po, custaddr_mst.city, Mv_Bc_Tag.lot , Mv_Bc_Tag_img_upload.file_name_1,"
                . " Mv_Bc_Tag_img_upload.file_name_2 "
                . " FROM Mv_Bc_Tag_img_upload "
                . " LEFT JOIN Mv_Bc_Tag ON Mv_Bc_Tag.id = Mv_Bc_Tag_img_upload.tag_id "
                . " LEFT JOIN item_mst ON item_mst.item = Mv_Bc_Tag.item "
                . " LEFT JOIN job_mst ON Mv_Bc_Tag.job = job_mst.job "
                . " LEFT JOIN co_mst ON co_mst.co_num = job_mst.ord_num "
                . " LEFT JOIN custaddr_mst ON custaddr_mst.cust_num = co_mst.cust_num and custaddr_mst.cust_seq = co_mst.cust_seq where 1=1 ";
        $query = $query . " and Mv_Bc_Tag_img_upload.lot like '%$lot%' ";
        if ($FromTagId != "" && $FromTagId != "") {
            $query = $query . "and ( Mv_Bc_Tag_img_upload.tag_id between '$FromTagId' and '$ToTagId' )";
        }
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetDOHeaderForCreateDoc($DoHeader_id) {
        $query = "select AST_ship.do_num , AST_ship.co_num , count(distinct(AST_ship.co_line)) as countCO, co_mst.cust_po as CUST_PO , ISNULL(co_mst.Uf_StsPO_refNo,'') as STS_PO,file_name_1,file_name_2 from AST_ship left join co_mst on AST_ship.co_num = co_mst.co_num left join Mv_Bc_Tag_img_upload_DoHeader on AST_ship.do_num = Mv_Bc_Tag_img_upload_DoHeader.DoHeader_id where do_num = '$DoHeader_id' group by AST_ship.do_num,AST_ship.co_num ,co_mst.cust_po ,co_mst.Uf_StsPO_refNo,file_name_1,file_name_2 ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetDOListForCreateDoc($DoHeader_id) {
        $query = "select distinct top 1000 isnull(AST_ship.tag_id,MV_Bc_Tag.id) as id, do_num ,AST_ship.co_num ,co_line  ,co_mst.cust_po as CUST_PO ,ISNULL(co_mst.Uf_StsPO_refNo,'') as STS_PO  ,AST_ship.item ,qty ,AST_ship.lot ,loc ,sts_no ,qty_sts_no ,sts_no2 ,qty_sts_no2,sts_no3 ,qty_sts_no3,item_mst.Uf_typeEnd,item_mst.Uf_NPS,item_mst.Uf_Grade,item_mst.Uf_Schedule,item_mst.Uf_length,item_mst.Uf_spec,Mv_Bc_Tag_img_upload.file_name_1,Mv_Bc_Tag_img_upload.file_name_2  from AST_ship left join mv_bc_tag on AST_ship.lot = mv_bc_tag.lot left join co_mst  on AST_ship.co_num = co_mst.co_num left join item_mst on item_mst.item = AST_ship.item left join Mv_Bc_Tag_img_upload on AST_ship.tag_id = Mv_Bc_Tag_img_upload.tag_id where do_num like '%$DoHeader_id%'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    Function DairyReportCountLotByWC() {
        $cSql = new SqlSrv();
        $query = "select w_c,count(STS_qty_move_line.doc_line) as totalLot  FROM STS_qty_move_hrd LEFT JOIN STS_qty_move_line ON STS_qty_move_hrd.doc_num = STS_qty_move_line.doc_num group by w_c";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function DairyReportCountLotByWCDate($w_c) {
        $cSql = new SqlSrv();
        $query = "select w_c,CONVERT(varchar, STS_qty_move_hrd.create_date, 111) as date,count(STS_qty_move_line.doc_line) as totalPerday FROM STS_qty_move_hrd  LEFT JOIN STS_qty_move_line ON STS_qty_move_hrd.doc_num = STS_qty_move_line.doc_num  "
                . " where w_c = '$w_c' "
                . " group by w_c,  CONVERT(varchar, STS_qty_move_hrd.create_date, 111) order by CONVERT(varchar, STS_qty_move_hrd.create_date, 111) desc ";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function DairyReportLine($w_c, $date) {
        $cSql = new SqlSrv();
        $query = "select CONVERT(varchar, STS_qty_move_hrd.create_date, 111),* "
                . " FROM STS_qty_move_hrd  LEFT JOIN STS_qty_move_line ON STS_qty_move_hrd.doc_num = STS_qty_move_line.doc_num  "
                . " where STS_qty_move_hrd.w_c = '$w_c' and CONVERT(varchar, STS_qty_move_hrd.create_date, 111) like  '%$date%' "
                . " order by STS_qty_move_hrd.id desc,STS_qty_move_line.id  asc ";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function STS_list_of_do_group() {
        $cSql = new SqlSrv();
        $query = "select * FROM STS_list_of_do_group order by id desc ";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }
    
    Function InsertSTS_list_of_do_group($do_group_num,$do_group_name,$do_group_list,$do_group_status) {
        $cSql = new SqlSrv();
        $query = " insert INTO  STS_list_of_do_group (do_group_num,do_group_name,do_group_list,do_group_status) "
                . " Values ('$do_group_num','$do_group_name','$do_group_list','$do_group_status') ";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }
    
    Function UpdateSTS_list_of_do_group($id,$do_group_num,$do_group_name,$do_group_list,$do_group_status) {
        $cSql = new SqlSrv();
        $query = "update STS_list_of_do_group "
                . " set do_group_num = '$do_group_num',do_group_name = '$do_group_name',do_group_list='$do_group_list',do_group_status= '$do_group_status' "
                . " where id = $id ";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }
    
    Function DeleteSTS_list_of_do_group($id) {
        $cSql = new SqlSrv();
        $query = "Delete FROM STS_list_of_do_group where id = $id ";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }
    
	Function SearchTagStatus($wh) {
        $cSql = new SqlSrv();
        $query = "select top 2000 mv_bc_tag.*, item.[description]
   , wc.[description] as wc
   , item.unit_weight, TotalWeight = case when mv_bc_tag.item like 'WS%' or mv_bc_tag.item like 'RS%' 
          then mv_bc_tag.qty1 
          else (isnull(mv_bc_tag.NC_QTY,0) * item.unit_weight) / 1000
          end
from mv_bc_tag 
 inner join item_mst item on item.item = mv_bc_tag.item
 inner join jobroute_mst job on job.job = mv_bc_tag.job and job.oper_num = 10
 inner join wc_mst wc on job.wc = wc.wc
where id <> ''".$wh."
order by id desc";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        $query2 = "select * from STS_QA_TAG_ISSUE";
        $rs2 = $cSql->SqlQuery($this->StrConn, $query2);
        $query3 = "select * from STS_QA_TAG_Minor";
        $rs3 = $cSql->SqlQuery($this->StrConn, $query3);
        array_splice($rs, count($rs) - 1, 1);
        array_splice($rs2, count($rs2) - 1, 1);
        array_splice($rs3, count($rs3) - 1, 1);
        return array($rs,$rs2,$rs3);
    }

    Function UpdateTagStatus($id,$status_value) {
        $cSql = new SqlSrv();
        $query = "update mv_bc_tag set tag_status = '".$status_value."' where id = '".$id."' ";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function SaveDetail($detail_id,$detail_value) {
        $cSql = new SqlSrv();
        $query = "update mv_bc_tag set detail = '".$detail_value."' where id = '".$detail_id."' ";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }
	
	Function UpdateBoat_Position($id,$doc_num,$boat_position) {
        $cSql = new SqlSrv();
        $query = "update STS_qty_move_line "
                . " set boat_position = '$boat_position' "
                . " where tag_id = $id and doc_num = '$doc_num' ";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }
	
	Function Search_STS_qty_move_hrd($doc_num) {
        $cSql = new SqlSrv();
        $query = "select * FROM STS_qty_move_hrd where doc_num = '$doc_num'";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }
	
	Function editActualWeight($doc_num, $eActualWeight) {
        $cSql = new SqlSrv();
        $query = "update STS_qty_move_hrd set ActWeight = $eActualWeight  where doc_num = '$doc_num'";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    // BoatNoteOnly 14/3/2024 bymay

    Function STS_qty_move_hrd_ship() {
        $cSql = new SqlSrv();
        $query = "select top 3500 doc_num, loc, create_date, doc_type, destination, round FROM STS_qty_move_hrd where doc_type = 'ship' order by doc_num desc ";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function moveqty_create_hdr_BoatNoteOnly($toLoc, $w_c, $doc_type, $do_num, $boatList,$destination,$ActWeight,$round) {
        if ($doc_type == "") {
            $doc_type == "Internal";
        }
        $cSql = new SqlSrv();
        $query = "exec STS_QtyMoveLotLocation_GEN_HEADER @loc = '$toLoc' , @w_c= '$w_c' ,@doc_type= '$doc_type' , @do_num='$do_num',@boatList='$boatList',@destination = '$destination',@ActWeight = '$ActWeight', @round = $round ";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        return $rs[0];
    }

    
    Function moveqty_create_line_BoatNoteOnly($tagnum, $toLoc, $boatPosition, $docline) {
        $query2 = " select top (1)* FROM STS_qty_move_hrd order by id desc";
        $cSql2 = new SqlSrv();
        $rs2 = $cSql2->SqlQuery($this->StrConn, $query2);

        $query = "EXEC [dbo].[STS_QtyMoveLotLocation_BoatNote]
		@docnum = N'".$rs2[1]["doc_num"]."',
		@docline = N'$docline',
		@tagNum = N'$tagnum',
		@toLoc = N'$toLoc',
		@boatPosition = N'$boatPosition'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        return $rs[0];
    }

    Function STS_qty_move_hrd_Truck() {
        $cSql = new SqlSrv();
        $query = "select top 3500 doc_num, loc, create_date, doc_type, destination FROM STS_qty_move_hrd where doc_type in ('Truck','Boat','Cont') order by doc_num desc ";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function moveqty_create_line_Truck($tagnum, $toLoc, $docline) {
        $query2 = " select top (1)* FROM STS_qty_move_hrd order by id desc";
        $cSql2 = new SqlSrv();
        $rs2 = $cSql2->SqlQuery($this->StrConn, $query2);

        $query = "EXEC [dbo].[STS_QtyMoveLotLocation_BoatNote]
		@docnum = N'".$rs2[1]["doc_num"]."',
		@docline = N'$docline',
		@tagNum = N'$tagnum',
		@toLoc = N'$toLoc'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        return $rs[0];
    }

    Function locEdit_Truck($loc, $doc_num) {

        if ($loc != ''){
        $queryhrd = "UPDATE STS_qty_move_hrd
        SET loc = '$loc'
        WHERE doc_num = '$doc_num'; ";
        $cSqlhrd = new SqlSrv();
        $rshrd = $cSqlhrd->SqlQuery($this->StrConn, $queryhrd);

        $query = "UPDATE STS_qty_move_line
        SET toloc = '$loc'
        WHERE doc_num = '$doc_num'; ";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    
    }

    function QcLabTagDetail($tag_id, $sts_no, $StartDate, $EndDate) {
         // search by StartDate and EndDate if not empty
        $query = "EXEC [dbo].[STS_lab_HRC]
@startDate = " . ($StartDate == "" ? "NULL" : "'" . $StartDate . "'") . ",
@endDate = " . ($EndDate == "" ? "NULL" : "'" . $EndDate . "'") . ",
@tag = " . ($tag_id == "" ? "NULL" : "'" . $tag_id . "'") . ",
@sts_no =  '" . $sts_no . "'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function SaveQc_Oper_Num($tag_id, $qc_oper_num) {
        $query = "update mv_bc_tag set qc_oper_num = '$qc_oper_num' where id = '$tag_id' ";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function getDataChart($StartDate, $EndDate) {
        $query = "WITH Months AS (
    SELECT 1 AS MonthNum UNION ALL
    SELECT 2 UNION ALL
    SELECT 3 UNION ALL
    SELECT 4 UNION ALL
    SELECT 5 UNION ALL
    SELECT 6 UNION ALL
    SELECT 7 UNION ALL
    SELECT 8 UNION ALL
    SELECT 9 UNION ALL
    SELECT 10 UNION ALL
    SELECT 11 UNION ALL
    SELECT 12
),
WCGroups AS (
    SELECT DISTINCT wcGroup 
    FROM V_STS_PROD_TIME_REPORT
    WHERE [date] BETWEEN '$StartDate' AND '$EndDate'
)
SELECT 
    m.MonthNum AS [month],
    w.wcGroup,
    ISNULL(SUM(v.sumA), 0) as sumA
FROM Months m
CROSS JOIN WCGroups w
LEFT JOIN V_STS_PROD_TIME_REPORT v 
    ON m.MonthNum = MONTH(v.[date])
    AND w.wcGroup = v.wcGroup
    AND v.[date] BETWEEN '$StartDate' AND '$EndDate'
GROUP BY m.MonthNum, w.wcGroup
ORDER BY m.MonthNum, w.wcGroup";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return array($rs);
    }

    function getGroupChart($StartDate, $EndDate, $GroupBy) {
        $query = "select *
from V_STS_PROD_TIME_REPORT
where [date] between '$StartDate' and '$EndDate'
  and wcGroup =  '$GroupBy'
order by [date]";
//$query1 = "select *
//from V_STS_PROD_TIME_REPORT
//where [date] between '$StartLastMonth' and '$EndLastMonth'
  //and wcGroup = '$GroupBy'
//order by [date]";

        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        //$rs1 = $cSql->SqlQuery($this->StrConn, $query1);

        array_splice($rs, count($rs) - 1, 1);
        return array($rs);
    }

    function getWorkCenters($StartDate, $EndDate) {
        $query = "select *
from V_STS_PROD_machine_stat
order by [date]";
        $query2 = 'select * from V_STS_machine_status';

        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        $rs1 = $cSql->SqlQuery($this->StrConn, $query2);

        array_splice($rs, count($rs) - 1, 1);
        array_splice($rs1, count($rs1) - 1, 1);
        return array($rs, $rs1);
    }

    function getGroupChart2($StartDate, $EndDate, $StartLastMonth, $EndLastMonth, $GroupBy) {
        $query = "EXEC [dbo].[STS_PROD_TIME_REPORT]
  @wcgroup = N'$GroupBy',
  @DateStarting = '$StartDate',
  @DateEnding = '$EndDate'";

        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);

        array_splice($rs, count($rs) - 1, 1);
        return array($rs);
    }

    function getDailyReport($date, $type) {
        if ($type == 'weight') {
            $query = "EXEC [dbo].[STS_PROD_TIME]
  @DateStarting = '$date',
  @DateEnding = '$date',
  @sumby = '1'";
        } else {
            $query = "EXEC [dbo].[STS_PROD_TIME]
  @DateStarting = '$date',
 @DateEnding = '$date',
  @sumby = '2'";
        }
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return array($rs);
    }
    function getDailyWorkCenter($StartDate, $EndDate, $wc,$type) {

        $query = "";

        if ($type == 'weight') {
            $query = "select V_STS_PROD_TIME_REPORT.work_hour,V_STS_PROD_TIME_REPORT.stop_hour,V_STS_PROD_TIME_REPORT.TOT_work_hour, V_STS_PROD_TIME_REPORT_HOURLY.wc, V_STS_PROD_TIME_REPORT_HOURLY.[description],V_STS_PROD_TIME_REPORT_HOURLY.[date]
  , [00:00-04:00] = max(case when V_STS_PROD_TIME_REPORT_HOURLY.work_hour = '00:00-04:00' then sumA_weight else 0 end)
  , [04:00-08:00] = max(case when V_STS_PROD_TIME_REPORT_HOURLY.work_hour = '04:00-08:00' then sumA_weight else 0 end)
  , [08:00-12:00] = max(case when V_STS_PROD_TIME_REPORT_HOURLY.work_hour = '08:00-12:00' then sumA_weight else 0 end)
  , [12:00-13:00] = max(case when V_STS_PROD_TIME_REPORT_HOURLY.work_hour = '12:00-13:00' then sumA_weight else 0 end)
  , [13:00-17:00] = max(case when V_STS_PROD_TIME_REPORT_HOURLY.work_hour = '13:00-17:00' then sumA_weight else 0 end)
  , [17:00-17:30] = max(case when V_STS_PROD_TIME_REPORT_HOURLY.work_hour = '17:00-17:30' then sumA_weight else 0 end)
  , [17:30-18:30] = max(case when V_STS_PROD_TIME_REPORT_HOURLY.work_hour = '17:30-18:30' then sumA_weight else 0 end)
  , [18:30-21:00] = max(case when V_STS_PROD_TIME_REPORT_HOURLY.work_hour = '18:30-21:00' then sumA_weight else 0 end)
  , [21:00-24:00] = max(case when V_STS_PROD_TIME_REPORT_HOURLY.work_hour = '21:00-24:00' then sumA_weight else 0 end)

from V_STS_PROD_TIME_REPORT_HOURLY inner join V_STS_PROD_TIME_REPORT on V_STS_PROD_TIME_REPORT.wc = V_STS_PROD_TIME_REPORT_HOURLY.wc and V_STS_PROD_TIME_REPORT.date = V_STS_PROD_TIME_REPORT_HOURLY.date
where (V_STS_PROD_TIME_REPORT_HOURLY.[date] between '$StartDate' and '$EndDate')
  and V_STS_PROD_TIME_REPORT_HOURLY.wc = '$wc'
group by V_STS_PROD_TIME_REPORT_HOURLY.wc,V_STS_PROD_TIME_REPORT_HOURLY.[description],V_STS_PROD_TIME_REPORT_HOURLY.[date],V_STS_PROD_TIME_REPORT.work_hour,V_STS_PROD_TIME_REPORT.stop_hour,V_STS_PROD_TIME_REPORT.TOT_work_hour
order by V_STS_PROD_TIME_REPORT_HOURLY.[date],V_STS_PROD_TIME_REPORT_HOURLY.wc";
        } else {
            $query = "select V_STS_PROD_TIME_REPORT.work_hour,V_STS_PROD_TIME_REPORT.stop_hour,V_STS_PROD_TIME_REPORT.TOT_work_hour, V_STS_PROD_TIME_REPORT_HOURLY.wc, V_STS_PROD_TIME_REPORT_HOURLY.[description],V_STS_PROD_TIME_REPORT_HOURLY.[date]
  , [00:00-04:00] = max(case when V_STS_PROD_TIME_REPORT_HOURLY.work_hour = '00:00-04:00' then sumA_qty else 0 end)
  , [04:00-08:00] = max(case when V_STS_PROD_TIME_REPORT_HOURLY.work_hour = '04:00-08:00' then sumA_qty else 0 end)
  , [08:00-12:00] = max(case when V_STS_PROD_TIME_REPORT_HOURLY.work_hour = '08:00-12:00' then sumA_qty else 0 end)
  , [12:00-13:00] = max(case when V_STS_PROD_TIME_REPORT_HOURLY.work_hour = '12:00-13:00' then sumA_qty else 0 end)
  , [13:00-17:00] = max(case when V_STS_PROD_TIME_REPORT_HOURLY.work_hour = '13:00-17:00' then sumA_qty else 0 end)
  , [17:00-17:30] = max(case when V_STS_PROD_TIME_REPORT_HOURLY.work_hour = '17:00-17:30' then sumA_qty else 0 end)
  , [17:30-18:30] = max(case when V_STS_PROD_TIME_REPORT_HOURLY.work_hour = '17:30-18:30' then sumA_qty else 0 end)
  , [18:30-21:00] = max(case when V_STS_PROD_TIME_REPORT_HOURLY.work_hour = '18:30-21:00' then sumA_qty else 0 end)
  , [21:00-24:00] = max(case when V_STS_PROD_TIME_REPORT_HOURLY.work_hour = '21:00-24:00' then sumA_qty else 0 end)

from V_STS_PROD_TIME_REPORT_HOURLY inner join V_STS_PROD_TIME_REPORT on V_STS_PROD_TIME_REPORT.wc = V_STS_PROD_TIME_REPORT_HOURLY.wc and V_STS_PROD_TIME_REPORT.date = V_STS_PROD_TIME_REPORT_HOURLY.date
where (V_STS_PROD_TIME_REPORT_HOURLY.[date] between '$StartDate' and '$EndDate')
  and V_STS_PROD_TIME_REPORT_HOURLY.wc = '$wc'
group by V_STS_PROD_TIME_REPORT_HOURLY.wc,V_STS_PROD_TIME_REPORT_HOURLY.[description],V_STS_PROD_TIME_REPORT_HOURLY.[date],V_STS_PROD_TIME_REPORT.work_hour,V_STS_PROD_TIME_REPORT.stop_hour,V_STS_PROD_TIME_REPORT.TOT_work_hour
order by V_STS_PROD_TIME_REPORT_HOURLY.[date],V_STS_PROD_TIME_REPORT_HOURLY.wc";
        }
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return array($rs);
    }

    function getParetoData($StartDate, $EndDate) {
        $query = "select STS_forming_reason_description.reason_description as reason_id, STS_forming_reason_description_detail.description as reason_detail_id,convert(varchar, time_stopped, 20) as time_stopped,time_used, w_c,ref_num,convert(varchar, create_date, 20) as create_date,remark FROM STS_forming_reason LEFT JOIN STS_forming_reason_description ON STS_forming_reason.reason_id = STS_forming_reason_description.reason_id LEFT JOIN STS_forming_reason_description_detail ON STS_forming_reason.reason_detail_id = STS_forming_reason_description_detail.reason_detail_id  where (time_stopped between '$StartDate' and '$EndDate')";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return array($rs);
    }

    function getParetoDataFinishing($StartDate, $EndDate,$GroupBy) {
        $query = "select STS_finishing_reason_description.reason_description as reason,convert(varchar, create_date, 20) as created_date,* from STS_finishing_reason LEFT JOIN STS_finishing_reason_description ON STS_finishing_reason_description.reason_id = STS_finishing_reason.reason_id where (time_stopped between '$StartDate' and '$EndDate') and w_c in ($GroupBy)"; ;
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return array($rs);
    }

    function getTableDataGroup($StartDate, $EndDate, $wc) {
        $query = "select *
from V_STS_PROD_TIME_REPORT_itemA
where [date] between '$StartDate' and '$EndDate'
  and wcGroup =  '$wc'
order by [date]";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return array($rs);
    }

    function UpdateIssue($id, $issue_value) {
        $query = "update mv_bc_tag set issue = '".$issue_value."' where id = '".$id."' ";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return array($rs);
    }

    function UpdateMinor($id, $minor_value) {
        $query = "update mv_bc_tag set Minor_cause = '".$minor_value."' where id = '".$id."' ";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return array($rs);
    }

    function UpdateMain($id, $main_value) {
        $query = "update mv_bc_tag set Main_cause = '".$main_value."' where id = '".$id."' ";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return array($rs);
    }

    function UpdateNC($id, $nc_value) {
        $query = "update mv_bc_tag set NC_QTY = '".$nc_value."' where id = '".$id."' ";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return array($rs);
    }

    function UpdateRecordDate($id, $record_date) {
        $query = "update mv_bc_tag set QA_RecordDate = '".$record_date."' where id = '".$id."' ";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return array($rs);
    }

    function GetQcDataAnalysisSummary($StartDate, $EndDate) {
        $query = "select Main_cause
   , Total=sum(total), REJECT=sum(REJECT), FIX=sum(FIX)
   , [NC ACCEPT]= sum([NC ACCEPT]), [in PROCESS]=sum([in PROCESS])
from V_STS_QA_TAG_MAIN_minor
where QA_RecordDate between '$StartDate' and '$EndDate'
group by Main_cause";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetQcDataAnalysisSummaryGroup($StartDate, $EndDate,$load) {
        $query = "select Main_cause, Minor_cause
   , Total=sum(total), REJECT=sum(REJECT), FIX=sum(FIX)
   , [NC ACCEPT]= sum([NC ACCEPT]), [in PROCESS]=sum([in PROCESS])
from V_STS_QA_TAG_MAIN_minor
where QA_RecordDate between '$StartDate'and '$EndDate'
   and main_cause = '$load'
group by Main_cause, Minor_cause";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function getWorkCentersStatus($date, $wc) {
        $query = "SELECT * FROM STS_machine_record 
WHERE CAST(start_date AS DATE) = '$date' 
AND wc = '$wc'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function SaveWorkCentersStatus($wc) {
        // insert into STS_machine_record then return result of insert
        $query = "INSERT INTO STS_machine_record(wc, start_date) 
OUTPUT inserted.*
VALUES('$wc', GETDATE());";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function SaveWorkCentersStatusClose($id) {
        // insert into STS_machine_record then return result of insert
        $query = "UPDATE STS_machine_record SET end_date = GETDATE() WHERE id = $id ";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function SaveWorkCentersStatusCancel($id) {
        // insert into STS_machine_record then return result of insert
        $query = "UPDATE STS_machine_record SET end_date = null WHERE id = $id ";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function getMachineReport($StartDate, $EndDate, $w_c, $type) {
        $query = "";

        if ($type == 2) {
            $query = "select * from STS_machine_record where start_date between '$StartDate' and '$EndDate'";
        } else {
            $query = "select * from STS_machine_record where start_date between '$StartDate' and '$EndDate' and wc = '$w_c'";
        }

        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }



 
	
}


