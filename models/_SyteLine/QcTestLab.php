<?php
class QcTestLab {
    var $StrConn = "";

    function setConn($c) {
        $this->StrConn = $c;
    }

    function SearchQcTestLab_Main($where){
        $query = "select sts_po_qc.grade, STS_QA_LAB.* 
from STS_QA_LAB inner join sts_po_qc
  on STS_QA_LAB.sts_no = sts_po_qc.sno and STS_QA_LAB.c_no = sts_po_qc.c_no
  and STS_QA_LAB.h_no = sts_po_qc.h_no ".$where."";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function SearchQcTestLab_Sub($where,$load){
        if ($load == "ReportSub"){
        $query = "select * from STS_QA_LAB_SUB b left join sts_qa_lab a  on a.sts_no = b.sts_no ".$where."";
        } else {
        $query = "select convert(varchar(10),convert(date, prod_date)) as prod_Date, * from STS_QA_LAB_SUB b left join sts_qa_lab a  on a.sts_no = b.sts_no ".$where."";
        }
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function InsertQcTestLab_Main($standard,$thick,$width,$weight,$sts_no,$c_no,$h_no,
    $sup_c,$sup_si,$sup_mn,$sup_p,$sup_s,$sup_cu,$sup_v,$sup_ni,$sup_cr,$sup_mo,$sup_ti,$sup_nb,$sup_al,$sup_b,$sup_co,$sup_pb,$sup_fe,$sup_ts,$sup_ys,$sup_el,
    $sts_c,$sts_si,$sts_mn,$sts_p,$sts_s,$sts_cu,$sts_v,$sts_ni,$sts_cr,$sts_mo,$sts_ti,$sts_nb,$sts_al,$sts_b,$sts_co,$sts_pb,$sts_fe,$sts_ts,$sts_ys,$sts_el) {
        
        $qSelect = "select * from STS_QA_LAB where sts_no = '$sts_no'";
        $cSql = new SqlSrv();
        $rsSelect = $cSql->SqlQuery($this->StrConn, $qSelect);

        if ( $sts_no == isset($rsSelect[1]["sts_no"])){
            $query ="UPDATE STS_QA_LAB SET standard = '$standard', thick = '$thick', width = '$width', weight = '$weight', sts_no = '$sts_no', c_no = '$c_no', h_no = '$h_no',
                    sup_c = '$sup_c', sup_si = '$sup_si', sup_mn = '$sup_mn', sup_p = '$sup_p', sup_s = '$sup_s', sup_cu = '$sup_cu', sup_v = '$sup_v', sup_ni = '$sup_ni', sup_cr = '$sup_cr', sup_mo = '$sup_mo', sup_ti = '$sup_ti', sup_nb = '$sup_nb', sup_al = '$sup_al', sup_b = '$sup_b', sup_co = '$sup_co', sup_pb = '$sup_pb', sup_fe = '$sup_fe', sup_ts = '$sup_ts', sup_ys = '$sup_ys', sup_el = '$sup_el',
                    sts_c = '$sts_c', sts_si = '$sts_si', sts_mn = '$sts_mn', sts_p = '$sts_p', sts_s = '$sts_s', sts_cu = '$sts_cu', sts_v = '$sts_v', sts_ni = '$sts_ni', sts_cr = '$sts_cr', sts_mo = '$sts_mo', sts_ti = '$sts_ti', sts_nb = '$sts_nb', sts_al = '$sts_al', sts_b = '$sts_b', sts_co = '$sts_co', sts_pb = '$sts_pb', sts_fe = '$sts_fe', sts_ts = '$sts_ts', sts_ys = '$sts_ys', sts_el = '$sts_el', ImportDate = Getdate()
                    where sts_no =  '$sts_no'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0; 
        }
        else{
         $query ="INSERT INTO STS_QA_LAB (standard,thick,width,weight,sts_no,c_no,h_no,"
                ."sup_c,sup_si,sup_mn,sup_p,sup_s,sup_cu,sup_v,sup_ni,sup_cr,sup_mo,sup_ti,sup_nb,sup_al,sup_b,sup_co,sup_pb,sup_fe,sup_ts,sup_ys,sup_el,"
                ."sts_c,sts_si,sts_mn,sts_p,sts_s,sts_cu,sts_v,sts_ni,sts_cr,sts_mo,sts_ti,sts_nb,sts_al,sts_b,sts_co,sts_pb,sts_fe,sts_ts,sts_ys,sts_el,ImportDate)"
                ."VALUES ('".$standard."', '".$thick."', '".$width."', '".$weight."', '".$sts_no."', '".$c_no."', '".$h_no."',"
                ."'".$sup_c."', '".$sup_si."', '".$sup_mn."', '".$sup_p."', '".$sup_s."', '".$sup_cu."', '".$sup_v."', '".$sup_ni."', '".$sup_cr."', '".$sup_mo."', '".$sup_ti."', '".$sup_nb."', '".$sup_al."', '".$sup_b."', '".$sup_co."', '".$sup_pb."', '".$sup_fe."', '".$sup_ts."', '".$sup_ys."', '".$sup_el."',"
                ."'".$sts_c."', '".$sts_si."', '".$sts_mn."', '".$sts_p."', '".$sts_s."', '".$sts_cu."', '".$sts_v."', '".$sts_ni."', '".$sts_cr."', '".$sts_mo."', '".$sts_ti."', '".$sts_nb."', '".$sts_al."', '".$sts_b."', '".$sts_co."', '".$sts_pb."', '".$sts_fe."', '".$sts_ts."', '".$sts_ys."', '".$sts_el."',Getdate())";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0; 
        }
    }

    function InsertQcTestLab_Sub($item,$opr_no,$size,$schedule,$length,$standard,$grade,$sts_no,
    $Mec_test_TS,$Mec_test_YS,$Mec_test_EI,$Mec_test_EL_1,$Mec_test_EL_2,$Mec_test_EL_3,
    $Not_Mec_test_TS,$Not_Mec_test_YS,$Not_Mec_test_EI,$Not_Mec_test_EL_1,$Not_Mec_test_EL_2,$Not_Mec_test_EL_3,$charpy_mean,$charpy1,$charpy2,$charpy3,$prod_FM_no,$prod_date,$test_date,$remark) {

        $qSelect = "select * from STS_QA_LAB_SUB where item = '$item' and opr_no = '$opr_no' and size = '$size' and sts_no = '$sts_no' and prod_FM_no = '$prod_FM_no' and length = '$length'  and convert(date,prod_date) = '$prod_date'";
        $cSql = new SqlSrv();
        $rsSelect = $cSql->SqlQuery($this->StrConn, $qSelect);

        if ( $opr_no == isset($rsSelect[1]["opr_no"]) && $sts_no == isset($rsSelect[1]["sts_no"]) && $prod_FM_no == isset($rsSelect[1]["prod_FM_no"]) && $length == isset($rsSelect[1]["length"]) && $prod_date == isset($rsSelect[1]["prod_date"])){
            $query ="UPDATE STS_QA_LAB_SUB SET item = '$item', opr_no = '$opr_no', size= '$size', Uf_Schedule= '$schedule', length= '$length', standard_sub= '$standard', Uf_Grade= '$grade', sts_no= '$sts_no', 
                    Mec_test_TS= '$Mec_test_TS', Mec_test_YS= '$Mec_test_YS', Mec_test_EI = '$Mec_test_EI', Mec_test_EL_1 = '$Mec_test_EL_1', Mec_test_EL_2 = '$Mec_test_EL_2', Mec_test_EL_3 = '$Mec_test_EL_3', 
                    Not_Mec_test_TS = '$Not_Mec_test_TS', Not_Mec_test_YS = '$Not_Mec_test_YS', Not_Mec_test_EI = '$Not_Mec_test_EI', Not_Mec_test_EL_1 = '$Not_Mec_test_EL_1', Not_Mec_test_EL_2 = '$Not_Mec_test_EL_2', Not_Mec_test_EL_3 = '$Not_Mec_test_EL_3', 
                    charpy_mean = '$charpy_mean', charpy1 = '$charpy1', charpy2 = '$charpy2', charpy3 = '$charpy3', prod_FM_no = '$prod_FM_no', prod_date = '$prod_date', test_date = '$test_date', remark = '$remark', ImportDate = Getdate()
                    where item = '$item' and opr_no = '$opr_no' and size = '$size' and sts_no = '$sts_no'  and prod_FM_no = '$prod_FM_no' and length = '$length'  and convert(date,prod_date) = '$prod_date'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0; 
        }
        else{
            $query ="INSERT INTO STS_QA_LAB_SUB (item,opr_no,size,Uf_Schedule,length,standard_sub,Uf_Grade,sts_no,Mec_test_TS,Mec_test_YS,Mec_test_EI,"
                    ."Mec_test_EL_1,Mec_test_EL_2,Mec_test_EL_3,Not_Mec_test_TS,Not_Mec_test_YS,Not_Mec_test_EI,Not_Mec_test_EL_1,Not_Mec_test_EL_2,Not_Mec_test_EL_3,charpy_mean,charpy1,charpy2,charpy3,prod_FM_no,prod_date,test_date,remark,ImportDate)"
                    ."VALUES ('".$item."','".$opr_no."','".$size."', '".$schedule."', '".$length."', '".$standard."', '".$grade."', '".$sts_no."', '".$Mec_test_TS."', '".$Mec_test_YS."','".$Mec_test_EI."',"
                    ."'".$Mec_test_EL_1."', '".$Mec_test_EL_2."', '".$Mec_test_EL_3."', '".$Not_Mec_test_TS."', '".$Not_Mec_test_YS."','".$Not_Mec_test_EI."', '".$Not_Mec_test_EL_1."', '".$Not_Mec_test_EL_2."', '".$Not_Mec_test_EL_3."', '".$charpy_mean."', '".$charpy1."', '".$charpy2."', '".$charpy3."', '".$prod_FM_no."', '".$prod_date."', '".$test_date."', '".$remark."',Getdate())";
            $cSql = new SqlSrv();
            $rs0 = $cSql->SqlQuery($this->StrConn, $query);
            array_splice($rs0, count($rs0) - 1, 1);
            return $rs0; 
        }
    }

    function ReportAll($where){
        $query = "select distinct ait.do_num, mv.item
        ,opr_no
        ,size
        ,b.Uf_Schedule
        ,length,standard_sub
        ,b.uf_grade
        ,a.sts_no,h_no,
              sts_c,sts_si,sts_mn,sts_p,sts_s,sts_cu,sts_v,sts_ni,sts_cr,sts_mo,
              sts_ti,sts_nb,sts_al,sts_b,sts_co,sts_pb,sts_fe,sts_ts,sts_ys,sts_el,
              Mec_test_TS,
              Mec_test_YS,
              Mec_test_EI,
              Mec_test_EL_1,
              Mec_test_EL_2,
              Mec_test_EL_3,
              Not_Mec_test_TS,
              Not_Mec_test_YS,
              Not_Mec_test_EI,
              Not_Mec_test_EL_1,
              Not_Mec_test_EL_2,
              Not_Mec_test_EL_3,
              charpy_mean,
              charpy1,
              charpy2,
              charpy3,
              prod_FM_no,
              prod_date,
              test_date,
              thick,
              width
     ,cust = case when co_mst.cust_num like 'EX%' then ca.addr##1 else ca.name end
       ,item.uf_typeEnd
      from do_seq_mst ait
         inner join matltrack_mst mtk on ait.ref_num = mtk.ref_num and ait.ref_line = mtk.ref_line_suf
    and mtk.date_seq = ait.date_seq and mtk.trans_date = ait.ship_date
    and mtk.qty < 0 and mtk.ref_type = 'O'
         inner join mv_bc_tag mv on mtk.item = mv.item and mtk.lot = mv.lot and mv.ship_stat = 1
   inner join item_mst item on item.item = mtk.item
  left join sts_qa_lab a  on (a.sts_no = mv.sts_no or a.sts_no = mv.sts_no2 or a.sts_no = mv.sts_no3)
     left join STS_QA_LAB_SUB b on a.sts_no = b.sts_no
    and mv.item like '%'+b.item+'%' 
  inner join co_mst on co_mst.co_num = ait.ref_num
  inner join custaddr_mst ca on ca.cust_num = co_mst.cust_num and ca.cust_seq = co_mst.cust_seq
          $where
      order by do_num, sts_no, item";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function makeReport($do_num){
        $query = "EXEC [dbo].[STS_QA_MILLCERT]
 @DONumStarting = N'$do_num'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function loadTest($do_num){
        $query = "select uf_QC_EDDY , uf_QC_HEAT, uf_QC_FLAT, uf_QC_BEND, uf_QC_VISUAL
from do_hdr_mst
where do_num = '$do_num'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function saveTest($do_num,$TestOne,$TestTwo,$TestThree,$TestFour, $TestFive){
        $query = "update do_hdr_mst
set uf_QC_EDDY = $TestOne
 , uf_QC_HEAT = $TestTwo
 , uf_QC_FLAT = $TestThree
 , uf_QC_BEND = $TestFour
 , uf_QC_VISUAL = $TestFive
where do_num = '$do_num'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function UpdateQcTestLab_Main($sts_no, $col_name, $valdata) {
        $query = "UPDATE STS_QA_LAB SET $col_name = '$valdata'  WHERE sts_no ='$sts_no' ";
        // $query = "update po_qc  set $col_name = '$valdata' where h_no in (select * FROM (select h_no from po_qc where sno ='$sno') as po_qc_tmp) "
        //         . " and  width in (select * FROM (select width from po_qc where sno ='$sno') as po_qc_tmp2)  ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function DeleteMain($sts_no) {
        $query = "DELETE from STS_QA_LAB WHERE sts_no ='$sts_no' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function DeleteSub($opr_no,$length, $sts_no, $prod_FM_no, $prod_Date,$size) {
        $query = "DELETE from STS_QA_LAB_SUB where opr_no = '$opr_no' and  sts_no = '$sts_no'  and prod_FM_no = '$prod_FM_no' and length = '$length' and size = '$size'  and convert(date,prod_date) = '$prod_Date'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function UpdateSub($opr_no,$length, $sts_no, $prod_FM_no, $prod_Date, $remark) {
        $query = "UPDATE STS_QA_LAB_SUB set remark = '$remark'  where opr_no = '$opr_no' and  sts_no = '$sts_no'  and prod_FM_no = '$prod_FM_no' and length = '$length'  and convert(date,prod_date) = '$prod_Date'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function UpdateSubTest($opr_no,$length, $sts_no, $prod_FM_no, $prod_Date, $form, $value) {
        $query = "UPDATE STS_QA_LAB_SUB set $form = $value where opr_no = '$opr_no' and  sts_no = '$sts_no'  and prod_FM_no = '$prod_FM_no' and length = '$length'  and convert(date,prod_date) = '$prod_Date'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetQAItemSpec() {
        $query = "select * from STS_QA_item_spec";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
  
    function AddQAItemSpec($itemSpec, $spec, $Grade,$SCH, $remark, $specCert) {
        $query = "INSERT INTO STS_QA_item_spec (itemSpec, spec, Grade, SCH, remark, specCert) VALUES ('$itemSpec', '$spec', '$Grade', '$SCH', '$remark', '$specCert')";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function UpdateQAItemSpecRemark($itemSpec,$remark) {
        $query = "UPDATE STS_QA_item_spec set remark = '$remark' where itemSpec = '$itemSpec'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function UpdateQAItemSpecCert($itemSpec,$specCert) {
        $query = "UPDATE STS_QA_item_spec set specCert = '$specCert' where itemSpec = '$itemSpec'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetZincCoating($StartDate, $EndDate) {
        $query = "select convert(date,matltran_mst.createdate) as prod_date
  ,matltran_mst.item, item.[description]
  ,matltran_mst.lot,tag.sts_no
from matltran_mst
   inner join mv_bc_tag tag on tag.lot = matltran_mst.lot 
   and tag.item = matltran_mst.item and tag.active = 1
   inner join item_mst item on item.item = matltran_mst.item
where trans_type = 'F'
     and matltran_mst.wc like '%GL%'
   and matltran_mst.item like 'w%071%'
   and (convert(date,matltran_mst.createdate) between  '$StartDate'  and '$EndDate')";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function InsertSTS_QC_zinc_coat($item, $lot,$sts_no,$QC_Pb,$QC_weight_coat) {
        $qSelect = "select * from STS_QC_zinc_coat where item = '$item' and lot = '$lot' and sts_no = '$sts_no'";
        $cSql = new SqlSrv();
        $rsSelect = $cSql->SqlQuery($this->StrConn, $qSelect);

        if ( $item == isset($rsSelect[1]["item"]) && $lot == isset($rsSelect[1]["lot"]) && $sts_no == isset($rsSelect[1]["sts_no"]) ) {
        $query = "UPDATE STS_QC_zinc_coat set QC_Pb = '$QC_Pb', QC_weight_coat = '$QC_weight_coat' where item = '$item' and lot = '$lot' and sts_no = '$sts_no'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
        } else {
            $query = "INSERT INTO STS_QC_zinc_coat (item, lot, sts_no, QC_Pb, QC_weight_coat,createdate) VALUES ('$item', '$lot', '$sts_no', $QC_Pb, $QC_weight_coat,getdate())";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
        }
        
    }

}
?>