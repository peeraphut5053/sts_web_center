<?php

//===================================================//
//===================================================//
//===================================================//
//=============== /SOD/SaleOrders Module   ===============//
//===================================================//
//===================================================//
//===================================================//
while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}


//============== Render Page Normal ================//
if (!isset($_POST["ajax"])) {
//    $temp->setReplace("{crumb}", "");
//    $temp->setReplace("{PageName}", "Sale Orders");
//    $temp->setReplace("{content}", $temp->getTemplate("./template/SOD/SaleOrders.html"));
} else {
//============== Render Ajax =======================//
    include "./initial.php";
    if (!isset($_SESSION)) {
        session_start();
    }

    if ($_POST["action"] == "GenNewCO") {
        $cust_num_prefix = substr($_POST["cust_num"], 0, 2);

        $result = "";
        $RunningDigit = 4;
        $CurrDate = date("ym");
        $FilSearch = $cust_num_prefix . $CurrDate;
        $sql = "SELECT TOP 1 co_num FROM  co_mst  WHERE co_num like '$FilSearch%' ORDER BY recorddate  DESC , co_num DESC  ";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($ConnSL, $sql);
        array_splice($rs, count($rs) - 1, 1);
        if (count($rs) == 0) {
            $result = $cust_num_prefix . $CurrDate . "0001";
        } else {
            $CurrCo = $rs[0]["co_num"];
            $CurrCoCutDate = substr($CurrCo, 6, 10);
            $val = str_pad(strval($CurrCoCutDate) + 1, $RunningDigit, '0', STR_PAD_LEFT);
            $result = $FilSearch . $val;
        }
        echo $result;
    }

    if ($_POST["action"] == "Upload") {
        $u_id = $_SESSION["login_user_id"];
        $site_ref = "STS";
        $err = 0;
        $errmsg = array();
        $type = "R";
        $co_num = $_POST["co_num"];
        $po_num = $_POST["po_num"];
        $cust_num = $_POST["cust_num"];
        $cust_seq = $_POST["cust_seq"];
        $doc_no = $_POST["doc_no"];
        $measure = $_POST["measure"];
        $due_date = $_POST["due_date"];
        $AllItems = explode("!!", $_POST["AllItems"]);
        $AllItemDesc = explode("!!", $_POST["AllItemDesc"]);
        $curr_code = "";


        //============== upload back to webapp========

        $cSql2 = new SqlSrv();
        $sql2 = "Update SO_Order_Head SET sl_co='$co_num',upload_date = GETDATE() , upload_by = $u_id  WHERE doc_no='$doc_no'  ";
        $resultUpHead = $cSql2->IsUpDel($ConnWebApp, $sql2);
        $resultUpHead = 0 ? $err++ : $err = $err;
        $cSql2 = null;
        //============== upload detail back to webapp========

        $cSql3 = new SqlSrv();
        $line = 0;
        $TmpItem = "";
        for ($i = 0; $i < count($AllItems); $i++) {
            $line = $i + 1;
            $AllItems[$i] ? $TmpItem = $AllItems[$i] : $TmpItem = "";
            $sql3 = "Update SO_Order_Detail SET sl_co='$co_num' , sl_item = '$TmpItem' WHERE doc_no='$doc_no' and line = $line ";

            $resIn = $cSql3->IsUpDel($ConnWebApp, $sql3);
            $resIn = 0 ? $err++ : $err = $err;
        }
        $cSql3 = null;
        //=======================================
        //\\/\/\/\\\\\/\\\///\\\ Disable Trigger \\\////\\\\////\\\\////\\\///\\\
        $DisableTriggerIns = sqlsrv_query($ConnSL, "DISABLE Trigger co_mst.co_mstInsert ON co_mst ");
        $DisableTriggerUpd = sqlsrv_query($ConnSL, "DISABLE Trigger co_mst.co_mstIup ON co_mst ");
        $DisableTriggerUpdl = sqlsrv_query($ConnSL, "DISABLE Trigger co_mst.co_mstUpdatePenultimate ON co_mst ");

        $DisableTriggerUpd2 = sqlsrv_query($ConnSL, "DISABLE Trigger co_item.coitem_mstIup ON co_item ");
        $DisableTriggerUpdl2 = sqlsrv_query($ConnSL, "DISABLE Trigger co_item.coitem_mstUpdatePenultimate ON co_item ");
        $date = date("Y-m-d");
        $dateTime = date("Y-m-d H:i:s");
        $cSql = new SqlSrv();
        $sql = "SELECT TOP 1 curr_code FROM custaddr_mst where cust_num = '$cust_num' ";
        //  echo $sql;
        $rs = $cSql->SqlQuery($ConnSL, $sql);
        array_splice($rs, count($rs) - 1, 1);
        if (count($rs) == 0) {
            $curr_code = "";
        } else {
            $curr_code = $rs[0]["curr_code"];
        }
        $sql = "INSERT INTO co_mst "
                . "("
                . "site_ref,"
                . "type,"
                . "co_num,"
                . "cust_po,"
                . "cust_num,"
                . "cust_seq,"
                . "order_date,"
                . "price,"
                . "whse,"
                . "orig_site,"
                . "CreatedBy,"
                . "UpdatedBy,curr_code ) "
                . "VALUES "
                . "('$site_ref',"
                . "'$type',"
                . "'$co_num',"
                . "'$po_num',"
                . "'$cust_num', "
                . "$cust_seq, "
                . "CONVERT(datetime, '$dateTime'), "
                . "0, "
                . "'MAIN', "
                . "'$site_ref', "
                . "'sa', "
                . "'sa','$curr_code' ) ";
        $resultSlHead = $cSql->IsUpDel($ConnSL, $sql);
        $resultSlHead = 0 ? $err++ : $err = $err;
        //==============Insert LOG
        $Log = new LogUpload();
        $Log->setConn($ConnWebApp);
        $sl_table = "co_mst";
        $msg = "Create Customer Order to Syteline  co_num =$co_num  ";
        $Log->Insert($msg,$sl_table);
        $Log = null;
        //===================
        //
            //
        //========insert co detail==========//
        $cSql = null;

        $cSql = new SqlSrv();
        $queryD = "SELECT * FROM SO_Order_Detail WHERE doc_no = '$doc_no' AND ( sl_item <> ''  OR sl_item <> null ) ";
        $tmpResult = $cSql->SqlQuery($ConnWebApp, $queryD);
        array_splice($tmpResult, count($tmpResult) - 1, 1);


        $x = 0;
        $Log = new LogUpload();
        $Log->setConn($ConnWebApp);
        foreach ($tmpResult as $ii => $rr) {
            $pieces = $rr["pieces"];
            $ext_price = $rr["ext_price"];
            $weight = $rr["theo_weight"];
            $price = $ext_price / $pieces;
            $m_tons = $rr["m_tons"];
            $m_tons2kg = $m_tons * 1000;
            $priceperkg = $ext_price / $m_tons2kg;
            $weight_per_pcs = $m_tons2kg / $pieces;
            $class = $rr["class"];
            $len = $rr["length"];
            $nps = $rr["size"];
            $od = $rr["od"];
            $sched = $rr["sched"];
            $cate = $rr["category"];
            $grade = ""; //?? why not have
            $tw = $rr["theo_weight"];
            $queryIn = "INSERT INTO coitem_mst ("
                    . "site_ref,"
                    . "co_num,"
                    . "co_line,"
                    . "co_release,"
                    . "item,"
                    . "qty_ordered,"
                    . "price,"
                    . "ref_type,"
                    . "due_date,"
                    . "reprice,"
                    . "stat,"
                    . "qty_ordered_conv,"
                    . "price_conv,"
                    . "co_cust_num,"
                    . "ship_site,"
                    . "co_orig_site,"
                    . "cust_po,"
                    . "description,"
                    . "CreatedBy,"
                    . "UpdatedBy,"
                    . "u_m,"
                    . "uf_um2,"
                    . "Uf_NetWeight,"
                    . "Uf_PricePerKg,"
                    . "Uf_WeightPerPcs,"
                    . "Uf_class,"
                    . "Uf_length, "
                    . "Uf_NPS, "
                    . "Uf_od,"
                    . "Uf_PriceFT, "
                    . "Uf_QTYFeet, "
                    . "Uf_QTYMT, "
                    . "Uf_Schedule, "
                    . "Uf_TotalPieces, "
                    . "Uf_TypeEnd, "
                    . "Uf_Wall, "
                    . "UF_NetPriceFT, "
                    . "UF_NetPriceMT, "
                    . "Uf_PriceMT, "
                    . "UF_THOEWeight, "
                    . "UF_ToleranceFrom, "
                    . "UF_ToleranceTo, "
                    . "Uf_spec, "
                    . "Uf_Grade"
                    . ") "
                    . "VALUES ("
                    . "'$site_ref',"
                    . "'$co_num',"
                    . "" . $rr["line"] . ","
                    . "0,"
                    . "'" . $AllItems[$x] . "',"
                    . "$pieces,"
                    . "$price,"
                    . "'I',"
                    . "CONVERT(datetime, '$due_date'),"
                    . "1,"
                    . "'O',"
                    . "$pieces,"
                    . "$price,"
                    . "'$cust_num',"
                    . "'$site_ref',"
                    . "'$site_ref',"
                    . "'$po_num',"
                    . "'" . $AllItemDesc[$x] . "',"
                    . "'sa',"
                    . "'sa',"
                    . "'$measure',"
                    . "'$measure',"
                    . "$m_tons,"
                    . "" . round($priceperkg, 5) . ","
                    . "$weight_per_pcs,"
                    . "'$class',"
                    . "'$len', "
                    . "'$nps', "
                    . "'$od',"
                    . "0, "
                    . "'$pieces', "
                    . "'$m_tons', "
                    . "'$sched', "
                    . "'$pieces', "
                    . "'$cate', "
                    . "'$tw', "
                    . "0, "
                    . "0, "
                    . "0, "
                    . "'$tw', "
                    . "0, "
                    . "0, "
                    . "'$class', "
                    . "'$grade'"
                    . ")  ";
            $cSqlIn = new SqlSrv();
            $resultSlDet = $cSqlIn->SqlQuery($ConnSL, $queryIn);
            $resultSlDet = 0 ? $err++ : $err = $err;
            $cSqlIn = null;
            $x++;

            //==============Insert LOG
            $sl_table = "coitem_mst";
            $msg = "Upload Customer Order to Syteline co_num =$co_num add item=" . $AllItems[$x] . " line=$x ";
            $Log->Insert($msg,$sl_table);
            //===================
        }
        $Log = null;
        $cSql = null;

        $EableTriggerIns = sqlsrv_query($ConnSL, "ENABLE Trigger co_mst.co_mstInsert ON co_mst ");
        $EableTriggerUpd = sqlsrv_query($ConnSL, "ENABLE Trigger co_mst.co_mstIup ON co_mst ");
        $EableTriggerUpdl = sqlsrv_query($ConnSL, "ENABLE Trigger co_mst.co_mstUpdatePenultimate ON co_mst ");
        $EnableTriggerUpd2 = sqlsrv_query($ConnSL, "ENABLE Trigger co_item.coitem_mstIup ON co_item ");
        $EnaableTriggerUpdl2 = sqlsrv_query($ConnSL, "ENABLE Trigger co_item.coitem_mstUpdatePenultimate ON co_item ");
        echo $err;
    }
    if ($_POST["action"] == "UploadMore") {
        $u_id = $_SESSION["login_user_id"];
        $site_ref = "STS";
        $err = 0;
        $errmsg = array();
        $type = "R";
        $co_num = $_POST["co_num"];
        $po_num = $_POST["po_num"];
        $cust_num = $_POST["cust_num"];
        $cust_seq = $_POST["cust_seq"];
        $doc_no = $_POST["doc_no"];
        $measure = $_POST["measure"];
        $due_date = $_POST["due_date"];
        $sl_co = $_POST["sl_co"];
        $SelItem = explode("!!", $_POST["SelItem"]);
        $AllItems = explode("!!", $_POST["AllItems"]);
        $AllLines = explode("!!", $_POST["AllLines"]);
        $curr_code = "";
        //============== upload back to webapp========

        $cSql2 = new SqlSrv();
        $sql2 = "Update SO_Order_Head SET sl_co='$sl_co',last_upload_date = GETDATE() , last_upload_by = $u_id  WHERE doc_no='$doc_no'  ";
        $resultUpHead = $cSql2->IsUpDel($ConnWebApp, $sql2);
        $resultUpHead = 0 ? $err++ : $err = $err;
        $cSql2 = null;



        //============== upload detail back to webapp========
        $cSql3 = new SqlSrv();
        $line = 0;
        $TmpItem = "";
        $TmpLine = 0;

        foreach ($SelItem as $ii => $rr) {
            $AllItems[$ii] ? $TmpItem = $AllItems[$ii] : $TmpItem = "";
            $AllLines[$ii] ? $TmpLine = (int) $AllLines[$ii] : $TmpLine = 0;
            $sql3 = "Update SO_Order_Detail SET sl_co='$sl_co' , sl_item = '$rr' WHERE doc_no='$doc_no' and line = $TmpLine ";
            $resIn = $cSql3->IsUpDel($ConnWebApp, $sql3);
            $resIn = 0 ? $err++ : $err = $err;
        }

        $cSql3 = null;
        //=======================================
        //\\/\/\/\\\\\/\\\///\\\ Disable Trigger \\\////\\\\////\\\\////\\\///\\\
        $DisableTriggerUpd2 = sqlsrv_query($ConnSL, "DISABLE Trigger co_item.coitem_mstIup ON co_item ");
        $DisableTriggerUpdl2 = sqlsrv_query($ConnSL, "DISABLE Trigger co_item.coitem_mstUpdatePenultimate ON co_item ");
        $date = date("Y-m-d");
        $dateTime = date("Y-m-d H:i:s");
        //========insert co detail==========//
        $cSql = null;
        $cSql = new SqlSrv();
        $Log = new LogUpload();
        $Log->setConn($ConnWebApp);
        foreach ($SelItem as $ii => $rr) {



            $AllItems[$ii] ? $TmpItem = $AllItems[$ii] : $TmpItem = "";
            $AllLines[$ii] ? $TmpLine = (int) $AllLines[$ii] : $TmpLine = 0;
            $queryD = "SELECT * FROM SO_Order_Detail WHERE doc_no = '$doc_no' AND sl_co='$sl_co' AND line =  $TmpLine ";
            $tmpResult = $cSql->SqlQuery($ConnWebApp, $queryD);
            array_splice($tmpResult, count($tmpResult) - 1, 1);
//            echo $queryD ;
//            
            $pieces = $tmpResult[0]["pieces"];
            $ext_price = round($tmpResult[0]["ext_price"], 5);
            $weight = $tmpResult[0]["theo_weight"];
            $price = $ext_price / $pieces;
            $price = round($price, 5);
            $m_tons = round($tmpResult[0]["m_tons"], 5);
            $m_tons2kg = $m_tons * 1000;
            $m_tons2kg = round($m_tons2kg, 5);
            $priceperkg = $ext_price / $m_tons2kg;
            $priceperkg = round($priceperkg, 5);
            $weight_per_pcs = $m_tons2kg / $pieces;
            $weight_per_pcs = round($weight_per_pcs, 5);
            $class = $tmpResult[0]["class"];
            $len = $tmpResult[0]["length"];
            $nps = $tmpResult[0]["size"];
            $od = $tmpResult[0]["od"];
            $sched = $tmpResult[0]["sched"];
            $cate = $tmpResult[0]["category"];
            $grade = ""; //?? why not have
            $tw = $tmpResult[0]["theo_weight"];
            $queryIn = "INSERT INTO coitem_mst ("
                    . "site_ref,"
                    . "co_num,"
                    . "co_line,"
                    . "co_release,"
                    . "item,"
                    . "qty_ordered,"
                    . "price,"
                    . "ref_type,"
                    . "due_date,"
                    . "reprice,"
                    . "stat,"
                    . "qty_ordered_conv,"
                    . "price_conv,"
                    . "co_cust_num,"
                    . "ship_site,"
                    . "co_orig_site,"
                    . "cust_po,"
                    . "description,"
                    . "CreatedBy,"
                    . "UpdatedBy,"
                    . "u_m,"
                    . "uf_um2,"
                    . "Uf_NetWeight,"
                    . "Uf_PricePerKg,"
                    . "Uf_WeightPerPcs,"
                    . "Uf_class,"
                    . "Uf_length, "
                    . "Uf_NPS, "
                    . "Uf_od,"
                    . "Uf_PriceFT, "
                    . "Uf_QTYFeet, "
                    . "Uf_QTYMT, "
                    . "Uf_Schedule, "
                    . "Uf_TotalPieces, "
                    . "Uf_TypeEnd, "
                    . "Uf_Wall, "
                    . "UF_NetPriceFT, "
                    . "UF_NetPriceMT, "
                    . "Uf_PriceMT, "
                    . "UF_THOEWeight, "
                    . "UF_ToleranceFrom, "
                    . "UF_ToleranceTo, "
                    . "Uf_spec, "
                    . "Uf_Grade"
                    . ") "
                    . "VALUES ("
                    . "'$site_ref',"
                    . "'$sl_co',"
                    . "" . $tmpResult[0]["line"] . ","
                    . "0,"
                    . "'$rr',"
                    . "$pieces,"
                    . "$price,"
                    . "'I',"
                    . "CONVERT(datetime, '$due_date'),"
                    . "1,"
                    . "'O',"
                    . "$pieces,"
                    . "$price,"
                    . "'$cust_num',"
                    . "'$site_ref',"
                    . "'$site_ref',"
                    . "'$po_num',"
                    . "'" . $tmpResult[0]["item_desc"] . "',"
                    . "'sa',"
                    . "'sa',"
                    . "'$measure',"
                    . "'$measure',"
                    . "$m_tons,"
                    . "" . round($priceperkg, 5) . ","
                    . "$weight_per_pcs,"
                    . "'$class',"
                    . "'$len', "
                    . "'$nps', "
                    . "'$od',"
                    . "0, "
                    . "'$pieces', "
                    . "'$m_tons', "
                    . "'$sched', "
                    . "'$pieces', "
                    . "'$cate', "
                    . "'$tw', "
                    . "0, "
                    . "0, "
                    . "0, "
                    . "'$tw', "
                    . "0, "
                    . "0, "
                    . "'$class', "
                    . "'$grade'"
                    . ")  ";
            $cSqlIn = new SqlSrv();
            $resultSlDet = $cSqlIn->SqlQuery($ConnSL, $queryIn);

            $resultSlDet = 0 ? $err++ : $err = $err;
            $cSqlIn = null;
            //==============Insert LOG
            $sl_table = "coitem_mst";
            $msg = "Upload Customer Order to Syteline  co_num =$sl_co add item=$rr line= " . $tmpResult[0]["line"];
            $Log->Insert($msg,$sl_table);
            //===================
        }
        $Log = null;
        $cSql = null;
        $EnableTriggerUpd2 = sqlsrv_query($ConnSL, "ENABLE Trigger co_item.coitem_mstIup ON co_item ");
        $EnaableTriggerUpdl2 = sqlsrv_query($ConnSL, "ENABLE Trigger co_item.coitem_mstUpdatePenultimate ON co_item ");
        echo $err;
    }
}