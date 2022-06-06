<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_POST["ajax"])) {
    $u_name = "";
    if ($_SESSION["login_username"]) {
        $u_name = $_SESSION["login_username"];
    } else {
        header("location: ?login");
    }

    $User = new User();
    $User->setConn($ConnWebApp);
    $User->GetProperties(" WHERE username = '$u_name' ");
    $HasCustomerLink = $User->cust_id;
    if ($HasCustomerLink == "") {
        $temp->setReplace("{content}", $temp->getTemplate("./template/partial/Error.html"));
    } else {
        $temp->setReplace("{content}", $temp->getTemplate("./template/SaleOrder.html"));


        $STS = new STS_Info();
        $STS->setConn($ConnWebApp);
        $STS->GetProperties();
        $StsAddr = $STS->addr1 . " " . $STS->addr2 . " " . $STS->addr3;
        $STS_Fullname = $STS->fullname;
        $STS_Name = $STS->name;
        $STS_Tel = $STS->tel1;
        $STS->tel2 ? $STS_Tel = $STS_Tel . " , " . $STS->tel2 : $STS_Tel;
        $STS_Fax = $STS->fax1;
        $STS->fax2 ? $STS_Fax = $STS_Fax . " , " . $STS->fax2 : $STS_Fax;

        if (isset($force_form)) {
            $temp->setReplace("{force_form}", $force_form);
            $temp->setReplace("{force_cust}", $force_cust);
            $temp->setReplace("{force_form_desc}", "<font color='orange'><b> Use XLS Form [ " . $force_form . " ] for [ " . $force_cust . " ]</b></font>");
        } else {
            $temp->setReplace("{force_form}", "");
            $temp->setReplace("{force_cust}", "");
            $temp->setReplace("{force_form_desc}", "");
        }

        $temp->setReplace("{cust_name}", $cust_name);
        $temp->setReplace("{cust_seq}", $cust_seq);
        $temp->setReplace("{cust_addr1}", $cust_addr1);
        

        $cust_num = "";
        $cust_measure = "";
        $link_cust = "";
        if (isset($force_cust)) {
            $cust_num = $force_cust;
            $CustModel = new CustomerSaleOrder();
            $CustModel->setConn($ConnWebApp);
            $CustModel->GetProperty(" WHERE cust_num ='$force_cust' ");
            $cust_measure = $CustModel->measure_desc_en;
            $link_cust = $CustModel->cust_num_sl;
//            echo print_r($CustModel);
            $CustModel = null;
        } else {
            $cust_num = $User->ref_so_custnum;
            $cust_measure = $User->measure_desc;
            $link_cust = $_SESSION["login_link_cust_num"];
        }


        $temp->setReplace("{STS_Fullname}", $STS_Fullname);
        $temp->setReplace("{STS_Addr}", $StsAddr);
        $temp->setReplace("{STS_Name}", $STS_Name);
        $temp->setReplace("{STS_Tel}", $STS_Tel);
        $temp->setReplace("{STS_Fax}", $STS_Fax);
        $temp->setReplace("{flag_duty}", $_SESSION["login_user_duty"]);
        $temp->setReplace("{cust_num}", $cust_num);
        $temp->setReplace("{cust_measure}", $cust_measure);
        $temp->setReplace("{back_main_link}", "?SOD/SOD/SaleOrderIndex");
        $temp->setReplace("{doc_date}", date('Y-m-d'));
        $temp->setReplace("{cust_num_sl}", $link_cust);
    }


} else {
//============== Render Ajax =======================//
    include "./initial.php";
    if ($_POST["action"] == "GetSite") {
        $Site = new Site();
        $Site->setConn($ConnWebApp);
        $GetSite = $Site->GetRows("");
        $data = "";
        $data = $data . "<option value='0'>.. Please select site ..</option>";
        foreach ($GetSite as $index => $rows) {
            $data = $data . "<option value='" . $rows["site_id"] . "'>" . $rows["site_name"] . " (" . $rows["site_measure"] . " ) </option>";
        }

        echo $data;
    }

    if ($_POST["action"] == "GetItems") {

        $Item = new ItemSyteline();
        $Item->setConn($ConnSL);
        $GetItem = $Item->GetRows("");
        $data = "";

        foreach ($GetItem as $index => $rows) {
            $data = $data . $rows["item"] . ",";
        }
        echo $data;
    }
    if ($_POST["action"] == "GetNewDocNo") {
        $Item = new OrderHead();
        $Item->setConn($ConnWebApp);
        $custnum = $_SESSION["login_cust_num"];
        $GetItem = $Item->GenNewDocNo($custnum);
        echo $GetItem;
    }
    if ($_POST["action"] == "ChangeStatus") {
        $Head = new OrderHead();
        $Head->setConn($ConnWebApp);
        $Head->_doc_no = $_POST["doc_no"];
        $Head->_status = $_POST["status"];
        $Result = $Head->ChangeStatus();
        echo $Result;
    }
    if ($_POST["action"] == "UpdateDetail") {
        $upd = "UPDATE SO_Order_detail SET "
                . "  item ='" . $_POST["item"] . "'"
                . ", item_desc ='" . $_POST["item_desc"] . "'"
                . ", category ='" . $_POST["category"] . "'"
                . ", size ='" . $_POST["size"] . "'"
                . ", theo_weight ='" . $_POST["theo_weight"] . "'"
                . ", length ='" . $_POST["length"] . "'"
                . ", length_total ='" . $_POST["length_total"] . "'"
                . ", sched ='" . $_POST["sched"] . "'"
                . ", pcs_per_bundle ='" . $_POST["pcs_per_bundle"] . "'"
                . ", bundles ='" . $_POST["bundles"] . "'"
                . ", pieces ='" . $_POST["pieces"] . "'"
                . ", m_tons ='" . $_POST["m_tons"] . "'"
                . ", cfr_lo_mt ='" . $_POST["cfr_lo_mt"] . "'"
                . ", cfr_lo_ft ='" . $_POST["cfr_lo_ft"] . "'"
                . ", ext_price ='" . $_POST["ext_price"] . "' "
                . ", lot ='" . $_POST["lot"] . "'"
                . " WHERE doc_no = '" . $_POST["doc_no"] . "' AND line= " . $_POST["line"] . " ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->IsUpDel($ConnWebApp, $upd);
        $cSql = null;
        echo $rs0;
//        
//        $Det = new OrderDetail();
//        $Det->__doc_no =$_POST["__doc_no"];
//        $Det->__item = $_POST["__item"];
//        $Det->__itemdesc = $_POST["__itemdesc"];
//        $Det->__category = $_POST["__category"];
//        $Det->__size = $_POST["__size"];
//        $Det->__theoweight = $_POST["__theoweight"];
//        $Det->__length = $_POST["__length"];
//        $Det->__lengthtotal = $_POST["__lengthtotal"];
//        $Det->__sched = $_POST["__sched"];
//        $Det->__pcsperbundle = $_POST["__pcsperbundle"];
//        $Det->__bundles = $_POST["__bundles"];
//        $Det->__pieces = $_POST["__pieces"];
//        $Det->__mtons = $_POST["__mtons"];
//        $Det->__cfrloft = $_POST["__cfrloft"];
//        $Det->__cfrlomt = $_POST["__cfrlomt"];
//        $Det->__lot = $_POST["__lot"];
//        $Det->__unitweights = $_POST["__unitweights"];
//        $Det->__extprice = $_POST["__extprice"];
//        $Det->__line = $_POST["__line"];
//        echo $Det->Update;
    }
    if ($_POST["action"] == "UpdateHeader") {
        $Head = new OrderHead();
        $Head->setConn($ConnWebApp);
        $Head->_po_num = $_POST["po_num"];
        $Head->_doc_no = $_POST["doc_no"];
        $Head->_po_date = $_POST["po_date"];
        $Head->_ship_to = $_POST["ship_to"];
        $Head->_ship_to_text = $_POST["ship_to_text"];
        $Head->_ShipExTo = $_POST["ShipExTo"];
        $Head->_ShipExFrom = $_POST["ShipExFrom"];
        $Head->_spec = $_POST["spec"];
        $Head->_attn = $_POST["attn"];
        $Head->_terms = $_POST["terms"];
        $Head->_remark = $_POST["remark"];
        echo $Head->Update();
    }
    if ($_POST["action"] == "SavePO") {

        $result = array();
        $result1 = "";
        $result2 = array();
        $head_data = explode("!!", $_POST["head_data"]);
        $user_id = $_SESSION["login_user_id"];

        $date = date("Y-m-d");
        $dateTime = date("Y-m-d H:i:s");
        $cSql = new SqlSrv();
        $sql = "INSERT INTO SO_Order_head "
                . "("
                . "cust_num,"
                . "doc_no,"
                . "po_num,"
                . "po_date,"
                . "ship_to,"
                . "ship_to_text,"
                . "attn,"
                . "spec,"
                . "term_of_po,"
                . "remark,"
                . "doc_date,"
                . "created_date,"
                . "active,"
                . "created_by,"
                . "status,"
                . "exmill_date_from,"
                . "exmill_date_to,"
                . "end_customer1,"
                . "end_customer2,"
                . "end_customer3,"
                . "end_customer4,"
                . "end_customer5,"
                . "end_customer6,"
                . "end_customer7,"
                . "end_customer8,"
                . "end_customer9,"
                . "end_customer10,xls_file,xls_sheet,sl_co) "
                . "VALUES "
                . "('" . $head_data[0] . "',"
                . "'" . $head_data[2] . "',"
                . "'" . $head_data[1] . "',"
                . "CONVERT(datetime,'" . $head_data[3] . "'),"
                . "" . $head_data[6] . ","
                . "'" . str_replace(",", " ", $head_data[7]) . "',"
                . "'" . $head_data[11] . "',"
                . "'" . $head_data[10] . "',"
                . "'" . $head_data[12] . "',"
                . "'" . $head_data[13] . "',"
                . "CONVERT(datetime,'$date'),"
                . "CONVERT(smalldatetime,'$dateTime'),"
                . "1,"
                . "$user_id,"
                . "'P',"
                . "'" . $head_data[8] . "',"
                . "'" . $head_data[9] . "',"
                . "'" . $head_data[14] . "',"
                . "'" . $head_data[15] . "',"
                . "'" . $head_data[16] . "',"
                . "'" . $head_data[17] . "',"
                . "'" . $head_data[18] . "',"
                . "'" . $head_data[19] . "',"
                . "'" . $head_data[20] . "',"
                . "'" . $head_data[21] . "',"
                . "'" . $head_data[23] . "',"
                . "'" . $head_data[23] . "',"
                . "'" . $head_data[24] . "',"
                . "'" . $head_data[25] . "',"
                . "'" . $head_data[26] . "') ";
        $result1 = $cSql->IsUpDel($ConnWebApp, $sql);

        //SaveDetail//
        $doc_no = $_POST["doc_no"];
        $lot = explode("!!", $_POST["lot"]);
        $item = explode("!!", $_POST["item"]);
        $item_desc = explode("!!", $_POST["item_desc"]);
        $size = explode("!!", $_POST["size"]);
        $category = explode("!!", $_POST["category"]);
        $tw = explode("!!", $_POST["tw"]);
        $length = explode("!!", $_POST["length"]);
        $length_total = explode("!!", $_POST["length_total"]);
        $sched = explode("!!", $_POST["sched"]);
        $pcs_per_bundle = explode("!!", $_POST["pcs_per_bndl"]);
        $bundles = explode("!!", $_POST["bndls"]);
        $pieces = explode("!!", $_POST["pieces"]);
        $m_tons = explode("!!", $_POST["mtons"]);
        $cfr_lo_mt = explode("!!", $_POST["cfrmt"]);
        $cfr_lo_ft = explode("!!", $_POST["cfrft"]);
        $unitweights = explode("!!", $_POST["unit_weight"]);
        $ext_price = explode("!!", $_POST["ext_price"]);
//        $sl_item = explode("!!", $_POST["txtSelItemSL"]);

        foreach ($item as $i => $r) {
            $x = $i + 1;
            $sql = "INSERT INTO SO_Order_detail "
                    . "("
                    . "doc_no,"
                    . "line,"
                    . "item,"
                    . "item_desc,"
                    . "category,"
                    . "size,"
                    . "theo_weight,"
                    . "length,"
                    . "length_total,"
                    . "sched,"
                    . "pcs_per_bundle,"
                    . "bundles,"
                    . "pieces,"
                    . "m_tons,"
                    . "cfr_lo_mt,"
                    . "cfr_lo_ft,"
                    . "ext_price,"
                    . "unit_weight,"
                    . "lot ) "
                    . "VALUES "
                    . "("
                    . "'$doc_no',"
                    . "$x,"
                    . "'$item[$i]',"
                    . "'$item_desc[$i]',"
                    . "'$category[$i]',"
                    . "'$size[$i]',"
                    . "'$tw[$i]',"
                    . "'$length[$i]',"
                    . "'$length_total[$i]',"
                    . "'$sched[$i]',"
                    . "'$pcs_per_bundle[$i]',"
                    . "'$bundles[$i]',"
                    . "'$pieces[$i]',"
                    . "'$m_tons[$i]',"
                    . "'$cfr_lo_mt[$i]',"
                    . "'$cfr_lo_ft[$i]',"
                    . "'$ext_price[$i]',"
                    . "'$unitweights[$i]',"
                    . "'$lot[$i]')";
            array_push($result2, $cSql->IsUpDel($ConnWebApp, $sql));
        }
        //Save LOG//
//            $Log = new LogOrder();
//            $Log->setConn($ConnWebApp);
//            $Log->_cust_num = $_SESSION["login_cust_num"];
//            $Log->_po_num = $head_data[1];
//            $Log->_doc_no = $_POST["doc_no"];
//            $result3 = $Log->Insert();
//            $Log = null;
//
//
        $sql = "INSERT INTO log_order "
                . "("
                . "user_id,"
                . "date_time,"
                . "cust_num,"
                . "po_num,"
                . "log_action,"
                . "doc_no ) "
                . "VALUES "
                . "($user_id,"
                . "CONVERT(datetime,'$dateTime'),"
                . "'$head_data[0]',"
                . "'$head_data[1]',"
                . "'Insert',"
                . "'$head_data[2]' ) ";

        $result = $cSql->IsUpDel($ConnWebApp, $sql);


        echo $result1;
    }
    if ($_POST["action"] == "DeleteRowDetail") {
        $sql = "DELETE FROM SO_Order_detail WHERE or_id = " . $_POST["id"] . "  ";
        $cSql = new SqlSrv();
        $result = $cSql->IsUpDel($ConnWebApp, $sql);
        return $result;
    }
    if ($_POST["action"] == "UpdateDetailRow") {
        $result = array();
        $doc_no = $_POST["doc_no"];
        $item = $_POST["item"];
        $item_desc = $_POST["item_desc"];
        $category = $_POST["category"];
        $size = $_POST["size"];
        $theo_weight = $_POST["theo_weight"];
        $length = $_POST["length"];
        $length_total = $_POST["length_total"];
        $sched = $_POST["sched"];
        $pcs_per_bundle = $_POST["pcs_per_bundle"];
        $bundles = $_POST["bundles"];
        $pieces = $_POST["pieces"];
        $m_tons = $_POST["m_tons"];
        $cfr_lo_mt = $_POST["cfr_lo_mt"];
        $cfr_lo_ft = $_POST["cfr_lo_ft"];
        $ext_price = $_POST["ext_price"];
        $unit_weight = $_POST["unit_weight"];
        $lot = $_POST["lot"];
        $CountItem = count($item);
        for ($i = 0; $i < $CountItem; $i++) {
            $SearchSql = "SELECT * FROM SO_Order_detail WHERE doc_no = '$doc_no' AND item='" . $item[$i] . "' AND lot='" . $lot[$i] . "' ";
            $cSql = new SqlSrv();
            $rs0 = $cSql->SqlQuery($ConnWebApp, $SearchSql);
            array_splice($rs0, count($rs0) - 1, 1);
            echo count($rs0);
            if (count($rs0) >= 1) { //duplicate in detail 
                $UpTW = (float) $rs0[0]["theo_weight"] + (float) $theo_weight[$i];
                $UpTTLen = (float) $rs0[0]["length_total"] + (float) $length_total[$i];
                $UpPCS = (float) $rs0[0]["pieces"] + (float) $pieces[$i];
                $UpMTons = (float) $rs0[0]["m_tons"] + (float) $m_tons[$i];
                $UpCFRMT = (float) $rs0[0]["cfr_lo_mt"] + (float) $cfr_lo_mt[$i];
                $UpCFRFT = (float) $rs0[0]["cfr_lo_ft"] + (float) $cfr_lo_ft[$i];
                $UpExtPrice = (float) $rs0[0]["ext_price"] + (float) $ext_price[$i];
                $sql = "UPDATE SO_Order_detail SET "
                        . "item='" . $item[$i] . "',"
                        . "item_desc='" . $item_desc[$i] . "',"
                        . "category='" . $category[$i] . "',"
                        . "size='" . $size[$i] . "',"
                        . "theo_weight='$UpTW',"
                        . "length='" . $length[$i] . "',"
                        . "length_total='$UpTTLen',"
                        . "sched='" . $sched[$i] . "',"
                        . "pcs_per_bundle='" . $pcs_per_bundle[$i] . "',"
                        . "bundles='" . $bundles[$i] . "',"
                        . "pieces='$UpPCS',"
                        . "m_tons='$UpMTons',"
                        . "cfr_lo_mt='$UpCFRMT',"
                        . "cfr_lo_ft='$UpCFRFT',"
                        . "ext_price='$UpExtPrice',"
                        . "unit_weight='" . $unit_weight[$i] . "'  "
                        . " WHERE doc_no = '$doc_no' AND lot ='" . $lot[$i] . "'  AND item ='" . $item[$i] . "' ";
                $cSqlUp = new SqlSrv();
                array_push($result, " Update Resule " . $cSqlUp->IsUpDel($ConnWebApp, $sql));
//                $result = $result . $cSql->IsUpDel($this->StrConn, $sql);
            } else {

                $SearchSql = "SELECT doc_no FROM SO_Order_detail WHERE doc_no = '$doc_no' ";
                $cSql = new SqlSrv();
                $rs0 = $cSql->SqlQuery($ConnWebApp, $SearchSql);
                array_splice($rs0, count($rs0) - 1, 1);
                $BeginId = count($rs0) + 1;
                $sql = "INSERT INTO SO_Order_detail "
                        . "("
                        . "doc_no,"
                        . "line,"
                        . "item,"
                        . "item_desc,"
                        . "category,"
                        . "size,"
                        . "theo_weight,"
                        . "length,"
                        . "length_total,"
                        . "sched,"
                        . "pcs_per_bundle,"
                        . "bundles,"
                        . "pieces,"
                        . "m_tons,"
                        . "cfr_lo_mt,"
                        . "cfr_lo_ft,"
                        . "ext_price,"
                        . "unit_weight,"
                        . "lot) "
                        . "VALUES "
                        . "("
                        . "'" . $_POST["doc_no"] . "',"
                        . "$BeginId,"
                        . "'" . $item[$i] . "',"
                        . "'" . $item_desc[$i] . "',"
                        . "'" . $category[$i] . "',"
                        . "'" . $size[$i] . "',"
                        . "'" . $theo_weight[$i] . "',"
                        . "'" . $length[$i] . "',"
                        . "'" . $length_total[$i] . "',"
                        . "'" . $sched[$i] . "',"
                        . "'" . $pcs_per_bundle[$i] . "',"
                        . "'" . $bundles[$i] . "',"
                        . "'" . $pieces[$i] . "',"
                        . "'" . $m_tons[$i] . "',"
                        . "'" . $cfr_lo_mt[$i] . "',"
                        . "'" . $cfr_lo_ft[$i] . "',"
                        . "'" . $ext_price[$i] . "',"
                        . "'" . $unit_weight[$i] . "',"
                        . "'" . $lot[$i] . "')";
                $cSqlIns = new SqlSrv();
                array_push($result, " Insert Resule " . $cSqlIns->IsUpDel($ConnWebApp, $sql));
            }
        }

        echo json_encode($result);
    }
    if ($_POST["action"] == "ExcelTableContent") {

        $ITEM = $_POST["ITEM"];
        $Lot = $_POST["Lot"];
        $tLot = $_POST["tLot"];
        $SIZE = $_POST["SIZE"];
        $WEIGHT = $_POST["WEIGHT"];
        $TW = $_POST["TW"];
        $LEN = $_POST["LEN"];
        $CATE = $_POST["CATE"];
        $SCHED = $_POST["SCHED"];
        $PCSPERBNDL = $_POST["PCSPERBNDL"];
        $BNDL = $_POST["BNDL"];
        $PIECES = $_POST["PIECES"];
        $TTLEN = $_POST["TTLEN"];
        $M_TONS = $_POST["M_TONS"];
        $FOBMT = $_POST["FOBMT"];
        $FOBFT = $_POST["FOBFT"];
        $extPrice = $_POST["extPrice"];


        $tdStyle = "style='border-top:none;border-bottom:none;'";
        $tblLines = "";
        $x = count($ITEM);
        $z = 0;
        $SumBundle = 0;
        $SumPieces = 0;
        $SumTTLen = 0;
        $SumMTons = 0;
        $SumFOBT = 0;
        $SumFOBFT = 0;
        $SumExtPrice = 0;

        for ($i = 0; $i < $x; $i++) {
            if ($ITEM[$i] && $SIZE[$i] && $WEIGHT[$i] && $LEN[$i]) {
                $z = $i + 1;
                $tblLines = $tblLines . "<tr id='rowIndex_$z'>";
                $tblLines = $tblLines . "<td width='3%' " . $tdStyle . " id='rowSIZE_$z' align='right'>$z</td>";
                $tblLines = $tblLines . "<td colspan='2' style='border-top:none;border-bottom:none;width:20%;' data-lot='" . $Lot[$i] . "'  id='rowItem_$z'>"
                        . "<input type='hidden' id='rowLot_" . $Lot[$i] . "' value='" . $Lot[$i] . "'>"
                        . "<input type='hidden' id='UnitWeight_$z' value='" . $WEIGHT[$i] . "'>"
                        . "<input type='hidden' id='rowDesc_$z' value='" . $ITEM_DESC[$i] . "'>"
                        . "<h6 style='font-size:12px;color:blue;' id='txtItem_$z'>" . $tLot[$i] . $ITEM[$i] . "</h6>"
                        . "<h6 style='font-size:12px;' id='txtDesc_$z'>" . $ITEM_DESC[$i] . "</h6>"
                        . "<h6 style='font-size:12px;' id='txtLot_$z'>" . $ITEM_LOT[$i] . "</h6>"
                        . "</td>";
                $tblLines = $tblLines . "<td width='3%' " . $tdStyle . " id='rowSIZE_$z' align='right'>" . $SIZE[$i] . "</td>";
                $tblLines = $tblLines . "<td width='3%' " . $tdStyle . " id='rowTW_$z'align='right'>" . $TW[$i] . "</td>";
                $tblLines = $tblLines . "<td width='3%' " . $tdStyle . " id='rowUL_$z'align='right'>" . $LEN[$i] . "</td>";
                $tblLines = $tblLines . "<td width='3%' " . $tdStyle . " id='rowCATE_$z'align='center'>" . $CATE[$i] . "</td>";
                $tblLines = $tblLines . "<td width='3%' " . $tdStyle . " id='rowSCHED_$z'align='right'>" . $SCHED[$i] . "</td>";
                $tblLines = $tblLines . "<td width='3%' " . $tdStyle . " id='rowPCSPERBNDL_$z'align='right'>" . $PCSPERBNDL[$i] . "</td>";
                $tblLines = $tblLines . "<td width='3%' " . $tdStyle . " id='rowBNDL_$z'align='right'>" . $BNDL[$i] . "</td>";
                $tblLines = $tblLines . "<td width='3%' " . $tdStyle . " id='rowPCS_$z'align='right'>" . $PIECES[$i] . "</td>";
                $tblLines = $tblLines . "<td width='3%' " . $tdStyle . " id='rowTTLEN_$z' align='right'>" . $TTLEN[$i] . "</td>";
                $tblLines = $tblLines . "<td width='3%' " . $tdStyle . " id='rowMTONS_$z'align='right'>" . $M_TONS[$i] . "</td>";
                $tblLines = $tblLines . "<td width='3%' " . $tdStyle . " id='rowCFRMT_$z'align='right'>" . $FOBMT[$i] . "</td>";
                $tblLines = $tblLines . "<td width='3%' " . $tdStyle . " id='rowCFRFT_$z' align='right'>" . $FOBFT[$i] . "</td>";
                $tblLines = $tblLines . "<td width='7%' " . $tdStyle . " id='rowEXTPRICE_$z'  align='right'>" . $extPrice[$i] . "</td>";
                $tblLines = $tblLines . "<td width='2%' style='border-top:none;border-bottom:none;' align='center'><a id='btnRemoveGridLines_$z' OnClick='RemoveThis($z);'  href='#' style='color:red;'><i class='fas fa-times'></i></a></td>";
                $tblLines = $tblLines . "</tr>";
                $SumBundle = $SumBundle + (float) str_replace(",", "", $BNDL[$i]);
                $SumPieces = $SumPieces + (float) str_replace(",", "", $PIECES[$i]);
                $SumTTLen = $SumTTLen + (float) str_replace(",", "", $TTLEN[$i]);
                $SumMTons = $SumMTons + (float) str_replace(",", "", $M_TONS[$i]);
                $SumFOBT = $SumFOBT + (float) str_replace(",", "", $FOBMT[$i]);
                $SumFOBFT = $SumFOBFT + (float) str_replace(",", "", $FOBFT[$i]);
                $SumExtPrice = $SumExtPrice + (float) str_replace(",", "", $extPrice[$i]);
            }
        }
        $tableSummary = "<tr>"
                . "<td colspan='9' align='right'><b>Total</b></td>"
                . "<td align='right'><b>" . number_format($SumBundle, 2) . "</b></td>"
                . "<td align='right'><b>" . number_format($SumPieces, 2) . "</b></td>"
                . "<td align='right'><b>" . number_format($SumTTLen, 2) . "</b></td>"
                . "<td align='right'><b>" . number_format($SumMTons, 2) . "</b></td>"
                . "<td align='right'><b>" . number_format($SumFOBT, 2) . "</b></td>"
                . "<td align='right'><b>" . number_format($SumFOBFT, 2) . "</b></td>"
                . "<td align='right'><b>" . number_format($SumExtPrice, 2) . "</b></td>"
                . "<td></td>"
                . "</tr>";
        echo $tblLines . $tableSummary;
    }
}



// $temp->setReplace("{ConSlVal}", $ConnSL);
