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
    $Mec_test_TS,$Mec_test_YS,$Mec_test_EI,$Mec_test_TS_2,$Mec_test_YS_2,$Mec_test_EL_2,
    $Not_Mec_test_TS,$Not_Mec_test_YS,$Not_Mec_test_EI,$Not_Mec_test_TS_2,$Not_Mec_test_YS_2,$Not_Mec_test_EL_2,$charpy_mean,$charpy1,$charpy2,$charpy3,$prod_FM_no,$prod_date,$test_date,$remark) {

        $qSelect = "select * from STS_QA_LAB_SUB where item = '$item' and opr_no = '$opr_no' and size = '$size' and sts_no = '$sts_no' and prod_FM_no = '$prod_FM_no' and length = '$length'  and convert(date,prod_date) = '$prod_date'";
        $cSql = new SqlSrv();
        $rsSelect = $cSql->SqlQuery($this->StrConn, $qSelect);

        if ($item == isset($rsSelect[1]["item"]) && $size == isset($rsSelect[1]["size"]) && $opr_no == isset($rsSelect[1]["opr_no"]) && $sts_no == isset($rsSelect[1]["sts_no"]) && $prod_FM_no == isset($rsSelect[1]["prod_FM_no"]) && $length == isset($rsSelect[1]["length"]) && $prod_date == isset($rsSelect[1]["prod_date"])){
            $query ="UPDATE STS_QA_LAB_SUB SET item = '$item', opr_no = '$opr_no', size= '$size', Uf_Schedule= '$schedule', length= '$length', standard_sub= '$standard', Uf_Grade= '$grade', sts_no= '$sts_no', 
                    Mec_test_TS= '$Mec_test_TS', Mec_test_YS= '$Mec_test_YS', Mec_test_EI = '$Mec_test_EI', Mec_test_TS_2 = '$Mec_test_TS_2', Mec_test_YS_2 = '$Mec_test_YS_2', Mec_test_EL_2 = '$Mec_test_EL_2', 
                    Not_Mec_test_TS = '$Not_Mec_test_TS', Not_Mec_test_YS = '$Not_Mec_test_YS', Not_Mec_test_EI = '$Not_Mec_test_EI', Not_Mec_test_TS_2 = '$Not_Mec_test_TS_2', Not_Mec_test_YS_2 = '$Not_Mec_test_YS_2', Not_Mec_test_EL_2 = '$Not_Mec_test_EL_2', 
                    charpy_mean = '$charpy_mean', charpy1 = '$charpy1', charpy2 = '$charpy2', charpy3 = '$charpy3', prod_FM_no = '$prod_FM_no', prod_date = '$prod_date', test_date = '$test_date', remark = '$remark', ImportDate = Getdate()
                    where item = '$item' and opr_no = '$opr_no' and size = '$size' and sts_no = '$sts_no'  and prod_FM_no = '$prod_FM_no' and length = '$length'  and convert(date,prod_date) = '$prod_date'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0; 
        }
        else{
            $query ="INSERT INTO STS_QA_LAB_SUB (item,opr_no,size,Uf_Schedule,length,standard_sub,Uf_Grade,sts_no,Mec_test_TS,Mec_test_YS,Mec_test_EI,"
                    ."Mec_test_TS_2,Mec_test_YS_2,Mec_test_EL_2,Not_Mec_test_TS,Not_Mec_test_YS,Not_Mec_test_EI,Not_Mec_test_TS_2,Not_Mec_test_YS_2,Not_Mec_test_EL_2,charpy_mean,charpy1,charpy2,charpy3,prod_FM_no,prod_date,test_date,remark,ImportDate,createDate)"
                    ."VALUES ('".$item."','".$opr_no."','".$size."', '".$schedule."', '".$length."', '".$standard."', '".$grade."', '".$sts_no."', '".$Mec_test_TS."', '".$Mec_test_YS."','".$Mec_test_EI."',"
                    ."'".$Mec_test_TS_2."', '".$Mec_test_YS_2."', '".$Mec_test_EL_2."', '".$Not_Mec_test_TS."', '".$Not_Mec_test_YS."','".$Not_Mec_test_EI."', '".$Not_Mec_test_TS_2."', '".$Not_Mec_test_YS_2."', '".$Not_Mec_test_EL_2."', '".$charpy_mean."', '".$charpy1."', '".$charpy2."', '".$charpy3."', '".$prod_FM_no."', '".$prod_date."', '".$test_date."', '".$remark."',Getdate(),Getdate())";
            $cSql = new SqlSrv();
            $rs0 = $cSql->SqlQuery($this->StrConn, $query);
            array_splice($rs0, count($rs0) - 1, 1);
            return $rs0; 
        }
    }

    function ReportAll($from_stsno, $to_stsno, $do_num, $h_no, $size, $standard, $prod_FM_no, $prod_date){
        $from_stsno = $from_stsno ?: null;
        $to_stsno = $to_stsno ?: null;
        $do_num = $do_num ?: null;
        $h_no = $h_no ?: null;
        $size = $size ?: null;
        $standard = $standard ?: null;
        $prod_FM_no = $prod_FM_no ?: null;
        $prod_date = $prod_date ?: null;
        
        $query = "EXEC STS_QA_MILLCERT_main
            @DONumStarting = " . ($do_num !== null ? "'$do_num'" : "null") . ",
            @STSnoStarting = " . ($from_stsno !== null ? $from_stsno : "null") . ",
            @STSnoEnding = " . ($to_stsno !== null ? $to_stsno : "null") . ",
            @H_no = " . ($h_no !== null ? "'$h_no'" : "null") . ",
            @size = " . ($size !== null ? "'$size'" : "null") . ",
            @standard_sub = " . ($standard !== null ? "'$standard'" : "null") . ",
            @prod_FM_no = " . ($prod_FM_no !== null ? "'$prod_FM_no'" : "null") . ",
            @prod_date = " . ($prod_date !== null ? "'$prod_date'" : "null");
        
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function makeReport($do_num, $co_num, $line_start, $line_end, $type){
        $query = "EXEC [dbo].[STS_QA_MILLCERT]
 @DONumStarting = N'$do_num'";

        if($type == 'bulk'){
            $query = "EXEC STS_QA_MILLCERT_BULK
  @DONumStarting = N'$do_num',
  @CONumStarting = " . ($co_num !== '' ? "N'$co_num'" : "NULL") . ",
  @COlineStarting = $line_start,
  @COlineEnding = $line_end";
        }

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function makeReportCanada($do_num){
        $query = "EXEC [dbo].[STS_QA_MILLCERT_CANADA]
 @DONumStarting = N'$do_num'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function makeReportCanadaBulk($do_num, $co_num, $line_start, $line_end, $type){
    
            $query = "EXEC [dbo].[STS_QA_MILLCERT_CANADA_BULK]
  @DONumStarting = N'$do_num',
  @CONumStarting = N'$co_num',
  @COlineStarting = $line_start,
  @COlineEnding = $line_end";
  
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function makePDF_Header($do_num){
        $query = "EXEC [dbo].[STS_QA_MILLCERT_HEAD]
  @donum = '$do_num'";
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
  ,zinc.QC_Pb, zinc.QC_weight_coat, zinc.createdate as import_date
from matltran_mst
   inner join mv_bc_tag tag on tag.lot = matltran_mst.lot 
    and tag.item = matltran_mst.item and tag.active = 1
   inner join item_mst item on item.item = matltran_mst.item
   left join STS_QC_zinc_coat zinc on zinc.item = tag.item and zinc.lot = tag.lot and zinc.sts_no = tag.sts_no
where trans_type = 'F'
     and matltran_mst.wc like '%GL%'
   and matltran_mst.item like 'w%'
   and substring(matltran_mst.item,4,2) = '71'
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

    function GetReportZinc($month, $month2, $y) {
        $query = "select * from STS_QA_LAB_ZINC where month(Date_rec) between '$month' and '$month2' and year(Date_rec) = '$y' order by Date_rec asc";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function InsertExcelZinc($sts_Pb, $sts_Al, $sts_Cd, $sts_Fe, $sts_Cu, $sts_Zn, $Test_result, $Date_rec, $Date_test, $LeadTime, $remark) {
        $qSelect = "select * from STS_QA_LAB_ZINC where Date_rec = '$Date_rec'";
        $cSql = new SqlSrv();
        $rsSelect = $cSql->SqlQuery($this->StrConn, $qSelect);

        if ($Date_rec == isset($rsSelect[1]["Date_rec"]) ) {
        $query = "UPDATE STS_QA_LAB_ZINC set sts_Pb = '$sts_Pb', sts_Al = '$sts_Al', sts_Cd = '$sts_Cd', sts_Fe = '$sts_Fe', sts_Cu = '$sts_Cu', sts_Zn = '$sts_Zn', Test_result = '$Test_result', Date_test = '$Date_test', LeadTime = '$LeadTime', remark = '$remark' where Date_rec = '$Date_rec'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
        } else {
        $query = "INSERT INTO STS_QA_LAB_ZINC (sts_Pb, sts_Al,sts_Cd,sts_Fe,sts_Cu,sts_Zn, Test_result, Date_rec, Date_test, LeadTime, remark, ImportDate) VALUES ('$sts_Pb', '$sts_Al', '$sts_Cd', '$sts_Fe', '$sts_Cu', '$sts_Zn', '$Test_result', '$Date_rec', '$Date_test', '$LeadTime', '$remark', getdate()) ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
        }
        
    }

    function GetLaboratory($StartDate, $EndDate) {
        $query = "EXEC [dbo].[STS_lab_Manufacturing_report]
  @startDate = N'$StartDate',
  @endDate = N'$EndDate'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function SaveDataQA_LAB_SUB($item, $sts_no, $size, $length, $prod_FM_no, $prod_Date, $val, $type) {
        $query = "UPDATE STS_QA_LAB_SUB set $type = '$val' where item = '$item' and sts_no = '$sts_no' and size = '$size' and length = '$length' and prod_FM_no = '$prod_FM_no' and convert(date,prod_date) = '$prod_Date'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function SaveCalibrationPlan( $measuring, $code_no, $s_n, $manufacturer, $model, $range, $range_cal, $acceptance, $due_date, $actual_cal, $next_cal, $company, $frequency, $loc_equipment, $onlyexternal) {
        // STS_QA_LAB_CalPlan
        $actual_cal_value = $actual_cal == '' ? 'NULL' : "'$actual_cal'";
        $next_cal_value = $next_cal == '' ? 'NULL' : "'$next_cal'";
        $due_date_value = $due_date == '' ? 'NULL' : "'$due_date'";

        // how to add N front $range = "'$range'";

        $query = "INSERT INTO STS_QA_LAB_CalPlan (Equipment, Code, SN, Manu, Model, RangeEquip, RangeCali, Acc_Cri, DueDate, ActCalDate, NextCalDate, Company, Frequency, Loc, RptNo)  VALUES ('$measuring', '$code_no', '$s_n', '$manufacturer', '$model', N'$range', N'$range_cal', N'$acceptance', $due_date_value, $actual_cal_value, $next_cal_value, '$company', '$frequency', '$loc_equipment', '$onlyexternal')";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetCalibrationPlan($StartDate, $EndDate, $ReportNo, $codeno) {
        // STS_QA_LAB_CalPlan

        $wh = '';

        if ($StartDate !== '' && $EndDate !== '') {
            $wh  = ' and DueDate between \'' . $StartDate . '\' and \'' . $EndDate . '\'';
        }

        if ($ReportNo !== '') {
            $wh  = ' and RptNo = \'' . $ReportNo . '\'';
        }

        if ($codeno !== '') {
            $wh  = ' and Code = \'' . $codeno . '\'';
        }

        $query = "select * from STS_QA_LAB_CalPlan where 1=1 $wh";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }
    function UpdateCalibrationPlan($measuring, $code_no, $s_n, $manufacturer, $model, $range, $range_cal, $acceptance, $due_date, $actual_cal, $next_cal, $company, $frequency, $loc_equipment, $onlyexternal, $old_onlyexternal) {
        $actual_cal_value = $actual_cal == '' ? 'NULL' : "'$actual_cal'";
        $next_cal_value = $next_cal == '' ? 'NULL' : "'$next_cal'";
        $due_date_value = $due_date == '' ? 'NULL' : "'$due_date'";
        $query = "UPDATE STS_QA_LAB_CalPlan 
          SET 
            Equipment = '$measuring',
            Code = '$code_no',
            SN = '$s_n',
            Manu = '$manufacturer',
            Model = '$model',
            RangeEquip = N'$range',
            RangeCali = N'$range_cal',
            Acc_Cri = N'$acceptance',
            DueDate = $due_date_value,
            ActCalDate = $actual_cal_value,
            NextCalDate = $next_cal_value,
            Company = '$company',
            Frequency = '$frequency',
            Loc = '$loc_equipment',
            RptNo = '$onlyexternal'
          WHERE 
            RptNo = '$old_onlyexternal'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function DeleteCalibrationPlan($RptNo) {
        $query = "DELETE FROM STS_QA_LAB_CalPlan WHERE RptNo = '$RptNo'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }


    function GetFormingSpeed($Item, $Size, $Spec, $Grade, $Thick) {

        $wh = '';

        if ($Item !== '') {
            $wh  = ' and fs.item = \'' . $Item . '\'';
        }

        if ($Size !== '') {
            $wh  = ' and size = \'' . $Size . '\'';
        }

        if ($Spec !== '') {
            $wh  = ' and spec = \'' . $Spec . '\'';
        }

        if ($Grade !== '') {
            $wh  = ' and grade = \'' . $Grade . '\'';
        }

        if ($Thick !== '') {    
            $wh  = ' and thick = \'' . $Thick . '\'';
        }

        $query = "select fs.* , size , spec, grade , thick 
 from STS_forming_speed fs  
   inner join  (select distinct item = substring(item,6,16),size = uf_NPS, spec= uf_class, grade = isnull(uf_grade,''), thick = isnull(uf_schedule,'')
       from item_mst 
      ) item
 on item.item = fs.item where 1=1 $wh";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function SelectQaDocuments($StartDate, $EndDate, $dept, $code,$type) {

        $wh = '';

        if ($StartDate !== '' && $EndDate !== '') {
            $wh  = ' and Issue_date between \'' . $StartDate . '\' and \'' . $EndDate . '\'';
        }

        if ($dept !== '') {
            $wh  = ' and Dept = \'' . $dept . '\'';
        }

        if ($code !== '') {
            $wh  = ' and Code = \'' . $code . '\'';
        }

        if ($type !== '') {
            $wh  = ' and Type = \'' . $type . '\'';
        }

        $query = "select * from STS_QA_Document where 1=1 $wh";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function InsertQaDocument($dept, $code, $title, $revision, $issue_date, $type) {
        // want result of insert
        $query = "INSERT INTO STS_QA_Document(Dept,Code,Title,revision,Issue_date,Type) OUTPUT inserted.* VALUES('$dept','$code','$title',$revision,'$issue_date','$type')";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function UpdateQaDocument($dept, $code, $title, $revision, $issue_date, $old_code, $type) {
        $query = "UPDATE STS_QA_Document SET Dept = '$dept', Code = '$code', Title = '$title', revision = $revision, Issue_date = '$issue_date', Type = '$type' OUTPUT inserted.* WHERE Code = '$old_code'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function DeleteQaDocument($code) {
        $query = "DELETE FROM STS_QA_Document WHERE Code = '$code'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function UploadFileQaDocument($code, $new_filename) {
        $query = "UPDATE STS_QA_Document SET [file]  = '$new_filename' WHERE Code = '$code'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetRowsWithCond2($Where) {
        $query = "SELECT qc.* , vendor_name = ven.name, ven.city
FROM STS_po_qc qc 
left join po_mst po on po.po_num = qc.upload_po
left join vendaddr_mst ven on ven.vend_num = po.vend_num
 $Where";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function getQaLabSub($prodDate, $importDate) {
        $query = "select * from STS_QA_LAB_SUB where 1=1";

        if($prodDate !== '') {
            $query .= " and convert(date,prod_date) = '$prodDate'";
        }
        if($importDate !== '') {
            $query .= " and convert(date,importDate) = '$importDate'";
        }

        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }
     function DeleteQaLabSub($opr_no,$item,$length, $sts_no, $prod_FM_no, $prod_Date,$size) {
        $query = "DELETE from STS_QA_LAB_SUB where opr_no = '$opr_no' and item = '$item' and  sts_no = '$sts_no' and prod_FM_no = '$prod_FM_no' and length = '$length' and size = '$size'  and convert(date,prod_date) = '$prod_Date'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function NewQaDocumentRequest($req, $doc, $user, $dept, $type, $code, $remark) {
        if (sqlsrv_begin_transaction($this->StrConn) === false) {
            die("Transaction failed: " . print_r(sqlsrv_errors(), true));
        }

        try {
            $year = date('y');
            $month = date('m');
            $prefix = "DC{$year}{$month}";

            // วิธีที่ 1: ใช้ Table Lock (แนะนำ)
            $lockSql = "SELECT TOP 1 1 FROM STS_QA_DocReq WITH (TABLOCKX)";
            $cSql = new SqlSrv();
            $cSql->SqlQuery($this->StrConn, $lockSql);

            // หรือวิธีที่ 2: Lock ด้วย Application Lock
            // $lockSql = "EXEC sp_getapplock @Resource='withdraw_doc_no', @LockMode='Exclusive', @LockTimeout=5000";
            // $cSql->SqlQuery($this->StrConn, $lockSql);

            // Query เลขล่าสุดหลัง lock แล้ว
            $sql = "SELECT TOP 1 ID 
                FROM STS_QA_DocReq 
                WHERE ID LIKE '{$prefix}%' 
                ORDER BY createdate DESC";

            $result = $cSql->SqlQuery($this->StrConn, $sql);
            array_splice($result, count($result) - 1, 1);

            if (count($result) > 0) {
                $lastDoc = $result[0]['ID'];
                $lastNumber = intval(substr($lastDoc, -3));
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }
            // DC2510001
            $docNumber = sprintf("DC%s%02d%04d", $year, $month, $newNumber);
         
            // Insert 
            $query = "INSERT INTO STS_QA_DocReq (ID, cat, doc, [user], dept, [type], code, remark) VALUES ('$docNumber', $req, '$doc', '$user', '$dept', '$type', '$code', '$remark')";
            $cSql->SqlQuery($this->StrConn, $query);
            sqlsrv_commit($this->StrConn);
            return $docNumber;
        } catch (Exception $e) {
            sqlsrv_rollback($this->StrConn);
            throw $e;
        }
    }

    function UpdateQaDocumentRequest($req, $doc, $user, $dept, $code, $prev_revision, $new_revision, $description, $remark,$type) {
        if (sqlsrv_begin_transaction($this->StrConn) === false) {
            die("Transaction failed: " . print_r(sqlsrv_errors(), true));
        }

        try {
            $year = date('y');
            $month = date('m');
            $prefix = "DC{$year}{$month}";

            // วิธีที่ 1: ใช้ Table Lock (แนะนำ)
            $lockSql = "SELECT TOP 1 1 FROM STS_QA_DocReq WITH (TABLOCKX)";
            $cSql = new SqlSrv();
            $cSql->SqlQuery($this->StrConn, $lockSql);

            // หรือวิธีที่ 2: Lock ด้วย Application Lock
            // $lockSql = "EXEC sp_getapplock @Resource='withdraw_doc_no', @LockMode='Exclusive', @LockTimeout=5000";
            // $cSql->SqlQuery($this->StrConn, $lockSql);

            // Query เลขล่าสุดหลัง lock แล้ว
            $sql = "SELECT TOP 1 ID 
                FROM STS_QA_DocReq 
                WHERE ID LIKE '{$prefix}%' 
                ORDER BY createdate DESC";

            $result = $cSql->SqlQuery($this->StrConn, $sql);
            array_splice($result, count($result) - 1, 1);

            if (count($result) > 0) {
                $lastDoc = $result[0]['ID'];
                $lastNumber = intval(substr($lastDoc, -3));
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }
            // DC2510001
            $docNumber = sprintf("DC%s%02d%04d", $year, $month, $newNumber);
         
            // Insert 
            $query = "INSERT INTO STS_QA_DocReq (ID, cat, doc, [user], dept, code, prev_rev, new_rev, Detail, remark, [type]) VALUES ('$docNumber', $req, '$doc', '$user', '$dept', '$code', '$prev_revision', '$new_revision', '$description', '$remark', '$type')";
            $cSql->SqlQuery($this->StrConn, $query);
            sqlsrv_commit($this->StrConn);
            return $docNumber;
        } catch (Exception $e) {
            sqlsrv_rollback($this->StrConn);
            throw $e;
        }
    }

    function GetReportQaDocumentRequest($StartDate, $EndDate, $ID, $cat, $type) {

        $wh = '';

        if ($StartDate !== '' && $EndDate !== '') {
            $wh  = $wh . "and createdate between '$StartDate' and '$EndDate' ";
        }

        if ($ID !== '') {
            $wh  = $wh . "and ID = '$ID' ";
        }

        if ($cat !== '') {
            $wh  = $wh . "and cat = '$cat' ";
        }

        if ($type !== '') {
            $wh  = $wh . "and type = '$type' ";
        }

        $query = "SELECT * FROM STS_QA_DocReq WHERE 1=1 $wh";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function UpdateNewQaDocumentRequest ($ID,$doc, $dept, $type, $code, $remark) {
        $query = "UPDATE STS_QA_DocReq SET doc = '$doc', dept = '$dept', type = '$type', code = '$code', remark = '$remark', updatedate = GETDATE() WHERE ID = '$ID'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }
    
    function EditUpdateQaDocumentRequest ($ID, $doc,  $dept,  $code, $prev_revision, $new_revision, $description, $remark, $type) {
        $query = "UPDATE STS_QA_DocReq SET doc = '$doc', dept = '$dept', code = '$code', prev_rev = '$prev_revision', new_rev = '$new_revision', Detail = '$description', remark = '$remark', [type] = '$type', updatedate = GETDATE() WHERE ID = '$ID'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function DeleteQaDocumentRequest ($ID) {
        $query = "DELETE FROM STS_QA_DocReq WHERE ID = '$ID'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function MgrApproveQaDocumentRequest ($ID, $user) {
        $query = "UPDATE STS_QA_DocReq SET Mgr_appr = '$user' WHERE ID = '$ID'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function QaApproveQaDocumentRequest ($ID, $user) {
        $query = "UPDATE STS_QA_DocReq SET Qa_appr = '$user' WHERE ID = '$ID'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function QaUploadQaDocumentRequest ($ID, $user) {
        $query = "UPDATE STS_QA_DocReq SET Qa_upload = 1 WHERE ID = '$ID'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }



}
?>