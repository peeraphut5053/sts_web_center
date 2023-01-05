<?php
class QcTestLab {
    var $StrConn = "";

    function setConn($c) {
        $this->StrConn = $c;
    }

    function SearchQcTestLab_Main($where){
        $query = "select * from STS_QA_LAB ".$where."";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function SearchQcTestLab_Sub($where){
        $query = "select * from STS_QA_LAB_SUB ".$where."";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function InsertQcTestLab_Main($standard,$thick,$width,$weight,$sts_no,$c_no,$h_no,
    $sup_c,$sup_si,$sup_mn,$sup_p,$sup_s,$sup_cu,$sup_v,$sup_ni,$sup_cr,$sup_mo,$sup_ti,$sup_nb,$sup_al,$sup_b,$sup_co,$sup_pb,$sup_fe,$sup_ts,$sup_ys,$sup_el,
    $sts_c,$sts_si,$sts_mn,$sts_p,$sts_s,$sts_cu,$sts_v,$sts_ni,$sts_cr,$sts_mo,$sts_ti,$sts_nb,$sts_al,$sts_b,$sts_co,$sts_pb,$sts_fe,$sts_ts,$sts_ys,$sts_el) {

         $query ="INSERT INTO STS_QA_LAB (standard,thick,width,weight,sts_no,c_no,h_no,"
                ."sup_c,sup_si,sup_mn,sup_p,sup_s,sup_cu,sup_v,sup_ni,sup_cr,sup_mo,sup_ti,sup_nb,sup_al,sup_b,sup_co,sup_pb,sup_fe,sup_ts,sup_ys,sup_el,"
                ."sts_c,sts_si,sts_mn,sts_p,sts_s,sts_cu,sts_v,sts_ni,sts_cr,sts_mo,sts_ti,sts_nb,sts_al,sts_b,sts_co,sts_pb,sts_fe,sts_ts,sts_ys,sts_el,ImportDate)"
                ."VALUES ('".$standard."', '".$thick."', '".$width."', ".$weight.", '".$sts_no."', '".$c_no."', '".$h_no."',"
                ."'".$sup_c."', '".$sup_si."', '".$sup_mn."', '".$sup_p."', '".$sup_s."', '".$sup_cu."', '".$sup_v."', '".$sup_ni."', '".$sup_cr."', '".$sup_mo."', '".$sup_ti."', '".$sup_nb."', '".$sup_al."', '".$sup_b."', '".$sup_co."', '".$sup_pb."', '".$sup_fe."', '".$sup_ts."', '".$sup_ys."', '".$sup_el."',"
                ."'".$sts_c."', '".$sts_si."', '".$sts_mn."', '".$sts_p."', '".$sts_s."', '".$sts_cu."', '".$sts_v."', '".$sts_ni."', '".$sts_cr."', '".$sts_mo."', '".$sts_ti."', '".$sts_nb."', '".$sts_al."', '".$sts_b."', '".$sts_co."', '".$sts_pb."', '".$sts_fe."', '".$sts_ts."', '".$sts_ys."', '".$sts_el."',Getdate())";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0; 
    }

    function InsertQcTestLab_Sub($opr_no,$size,$thick,$length,$standard,$sts_no,
    $Mec_test_TS,$Mec_test_YS,$Mec_test_EI,$Mec_test_EL_1,$Mec_test_EL_2,$Mec_test_EL_3,
    $Not_Mec_test_TS,$Not_Mec_test_YS,$Not_Mec_test_EI,$Not_Mec_test_EL_1,$Not_Mec_test_EL_2,$Not_Mec_test_EL_3,$charpy_mean,$charpy1,$charpy2,$charpy3,$Metal_P,$Metal_F,$Metal_M,$Hydro_test,$prod_FM_no,$prod_date,$test_date) {

            $query ="INSERT INTO STS_QA_LAB_SUB (opr_no,size,thick_sub,length,standard_sub,sts_no,Mec_test_TS,Mec_test_YS,Mec_test_EI,"
                    ."Mec_test_EL_1,Mec_test_EL_2,Mec_test_EL_3,Not_Mec_test_TS,Not_Mec_test_YS,Not_Mec_test_EI,Not_Mec_test_EL_1,Not_Mec_test_EL_2,Not_Mec_test_EL_3,charpy_mean,charpy1,charpy2,charpy3,Metal_P,Metal_F,Metal_M,Hydro_test,prod_FM_no,prod_date,test_date,ImportDate)"
                    ."VALUES ('".$opr_no."','".$size."', '".$thick."', '".$length."', '".$standard."', '".$sts_no."', '".$Mec_test_TS."', '".$Mec_test_YS."','".$Mec_test_EI."',"
                    ."'".$Mec_test_EL_1."', '".$Mec_test_EL_2."', '".$Mec_test_EL_3."', '".$Not_Mec_test_TS."', '".$Not_Mec_test_YS."','".$Not_Mec_test_EI."', '".$Not_Mec_test_EL_1."', '".$Not_Mec_test_EL_2."', '".$Not_Mec_test_EL_3."', '".$charpy_mean."', '".$charpy1."', '".$charpy2."', '".$charpy3."', '".$Metal_P."', '".$Metal_F."', '".$Metal_M."', '".$Hydro_test."', '".$prod_FM_no."', '".$prod_date."', '".$test_date."',Getdate())";
            $cSql = new SqlSrv();
            $rs0 = $cSql->SqlQuery($this->StrConn, $query);
            array_splice($rs0, count($rs0) - 1, 1);
            return $rs0; 
    }
}
?>