<?php

//===================================================//
//===================================================//
//===================================================//
//=============== ItemSyteline.php      =============//
//===================================================//
//===================================================//
//===================================================//
// t-sql for get column name
//
// declare @aa varchar (MAX)
// set @aa = ''
//
// select @aa =
//     case when @aa = ''
//     then COLUMN_NAME
//     else @aa + coalesce(',' + COLUMN_NAME, '')
//     end
//   from Train20_AppDemo.INFORMATION_SCHEMA.COLUMNS
// WHERE TABLE_NAME = N'item_mst'
// print @aa

    set_time_limit(0);
ini_set('memory_limit', '200M');
class ItemSyteLine {

    var $StrConn = "";
    public $_item = "";
    public $_items = array();
    public $_site_ref = "";
    public $_item_category = "";
    public $_Uf_grade = "";
    public $_Uf_sched = "";
    public $_Uf_od = "";
    public $_Uf_spec = "";
    public $_Uf_length = "";
    public $_Uf_Nps = "";
    public $_rpt_item = "";
    public $_rpt_size = "";
    public $_thick = "";
    public $_width = "";
    public $_rpt_thick = "";
    public $_rpt_width = "";
    public $_cust_name = "";
    public $_rpt_stock = "";
    public $_rpt_item_desc = "";
    public $_location = "";
    public $_rpt_thick_from = "";
    public $_rpt_thick_to = "";
    public $_locations = array();
    public $_start_shipdate = "";
    public $_end_shipdate = "";
    public $_start_inv_date = "";
    public $_end_inv_date = "";
    public $_atYear = "";
    public $_cb = "";
    public $_StartDate = "";
    public $_ToDate = "";
    public $_Customers = array();
    public $_LotS = array();
    public $_ItemS = array();
    public $_check_tag = "";
    public $_alldata_lot = array();
    public $_alldata_item = array();
    public $_alldata_onhand = array();
    public $_alldata_tag = array();
    public $_alldata_mfg_date = array();
    public $_alldata_print_date = array();
    public $_alldata_grade = array();
    public $_alldata_coilno = array();
    public $_alldata_thick = array();
    public $_alldata_width = array();
    public $_alldata_spec = array();
    public $_alldata_qty2 = array();
    public $_DataInsert = array();
    public $_ArrItems = array();

    public function setConn($c) {
        $this->StrConn = $c;
    }

    function GetRows($sWhere) {
        $query = "SELECT * FROM item_mst $sWhere ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
    function GlobalSearchItem($sWhere) {
        $query = "SELECT   "
                . "it.item as Item "
                . ", Description "
                . ", cast( iw.qty_on_hand as decimal(23,2)) as Qty "
                . ", it.U_M as Um FROM item_mst it "
                . "LEFT JOIN itemwhse_mst iw on it.item = iw.item   $sWhere ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
    function GetRowsByItem() {
        $query = "SELECT * FROM item_mst  where item + description LIKE '%" . $this->_item . "%' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetItemNpsForDropdown() {
        $query = "select  replace(Uf_NPS,'''' ,'')  as size   "
                . "from item_mst  "
                . "where    "
                . "Uf_NPS not like '%x%'  "
                . "and Uf_NPS not like '%X%'  "
                . "and Uf_NPS not like '%OD%' "
                . "and Uf_NPS not like '%''%' "
                . "and Uf_NPS not like '%#%' "
                . "group by Uf_NPS";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetItemPropertyForFilter($prop, $cate) {
        $Cate = $this->_item_category;
        $query = "select $prop FROM  item_mst  item "
//                . "LEFT JOIN  item_category_item_mst ici ON (item.item = ici.item) "
//                . "LEFT JOIN  item_category_mst ic ON (ici.item_category = ic.item_category) "
//                . "WHERE  ici.item_category='$cate' AND  $prop is not null  "
                . " WHERE Uf_TypeEnd =  '$cate' "
                . "GROUP BY $prop order by $prop asc";

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        $returnArray = array();
        $tmpKey = "";
        $tempValue = "";
        $returnArray[""] = "";
        foreach ($rs0 as $index => $rows) {
            $tmpKey = $rows[$prop];
            $tempValue = $rows[$prop];
            $returnArray[$tmpKey] = $tempValue;
        }
        return $returnArray;
    }

    function CheckItemLoc() {
        $tag = $this->_check_tag;
        $query = "select * FROM V_WebApp_WH_Check_ItemLoc   "
                . "WHERE id = '$tag'  ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function SaveItemNonWeight() {
        $ArrItems = $this->_ArrItems;
        $result = 0;
        $query = "";
        $ret = "";
        $cSql = new SqlSrv();
        $countRec = count($ArrItems);
        foreach ($ArrItems as $ii => $rr) {

            $query = "UPDATE item_mst SET Uf_Unit_Weight =" . $rr[1] . " WHERE item= '" . $rr[0] . "'  ";
            $result += $cSql->IsUpDel($this->StrConn, $query);
        }
        if ($result == $countRec) {
            $ret = "Complete";
        } else {
            $ret = "Fail";
        }
        return $ret;
    }

    function check_item_old_bc() {
        $cSql = new SqlSrv();
        $sql = "";
        $lots = array();
        $items = array();
        $lots = $this->_LotS;
        $items = $this->_ItemS;
        $x = count($items);
        $ArrResult = array();
        if ($x > 0) {
            for ($i = 0; $i < $x; $i++) {
                $sql = "select "
                        . "isnull(b.Uf_Grade,'') as grade  , "
                        . "isnull(Uf_thickness,0) as thick  , "
                        . "isnull(Uf_width,0) as width , "
                        . "isnull(Uf_spec,'') as spec , "
                        . "ISNULL(a.rcvd_qty,0) as qty2 , "
                        . "ISNULL(b.u_m,'') as u_m , "
                        . "ISNULL(b.uf_um2,'') as u_m2 ,"
                        . "ISNULL(b.uf_pack,0) as uf_pack "
                        . " from lot_mst a left join item_mst b on a.item = b.item "
                        . " where replace(a.item,' ','') ='" . $items[$i] . "' and replace(lot,' ','') ='" . $lots[$i] . "' ";
                $rs = $cSql->SqlQuery($this->StrConn, $sql);
                array_splice($rs, count($rs) - 1, 1);
                $arrLoop = array();
                if ($rs) {
                    array_push($arrLoop, $rs[0]["u_m"]);
                    array_push($arrLoop, $rs[0]["u_m2"]);
                    array_push($arrLoop, $rs[0]["grade"]);
                    array_push($arrLoop, $rs[0]["thick"]);
                    array_push($arrLoop, $rs[0]["width"]);
                    array_push($arrLoop, $rs[0]["spec"]);
                    array_push($arrLoop, $rs[0]["qty2"]);
                    array_push($arrLoop, $rs[0]["uf_pack"]);
                } else {
                    array_push($arrLoop, "N/A");
                    array_push($arrLoop, "N/A");
                    array_push($arrLoop, "N/A");
                    array_push($arrLoop, "N/A");
                    array_push($arrLoop, "N/A");
                    array_push($arrLoop, "N/A");
                    array_push($arrLoop, "N/A");
                    array_push($arrLoop, "N/A");
                }
                array_push($ArrResult, $arrLoop);
            }
        }
        $cSql = null;
        return $ArrResult;
    }

    function insert_item_old_bc() {
        $cSql = new SqlSrv();
        $sql = "";
        $DataInsert = $this->_DataInsert;
        $ArrResult = array();
        $tmp = "";
//        echo print_r($DataInsert);
        $rs = 0;
        $totalResult = 0;
        foreach ($DataInsert as $ii => $rr) {
            $sql = "INSERT INTO  Mv_Bc_Tag ( "
                    . "id, item, lot, "
                    . "qty1, qty2, um2,  "
                    . "uf_grade, uf_pack, mfg_date, "
                    . "print_date, uf_spec, uf_Tickness, "
                    . "uf_width ) "
                    . "VALUES ("
                    . "'" . $rr[5] . "','" . $rr[1] . "','" . $rr[0] . "'"
                    . "," . $rr[2] . "," . $rr[12] . ",'" . $rr[4] . "'"
                    . ",'" . $rr[8] . "'," . $rr[13] . ",'" . $rr[6] . "'"
                    . ",'" . $rr[7] . "','" . $rr[11] . "'," . $rr[9] . ","
                    . "" . $rr[10] . ""
                    . ") ";
            $rs = $cSql->IsUpDel($this->StrConn, $sql);
            $totalResult += $rs;
        }
        return "Complete";
    }

    function GetItems() {
        $Cate = $this->_item_category;
        $Grade = $this->_Uf_grade;
        $NPS = $this->_Uf_Nps;
        $SCHED = $this->_Uf_sched;
        $pGrade = " AND Uf_Grade = '$Grade' ";
        $pNPS = " AND Uf_Nps = '$NPS' ";
        $pSCHED = " AND Uf_Schedule = '$SCHED' ";
        $pCATE = " AND Uf_TypeEnd ='$Cate' ";

        if ($Grade == "") {
            $pGrade = "";
        }
        if ($NPS == "") {
            $pNPS = "";
        }
        if ($SCHED == "") {
            $pSCHED = "";
        }
        $query = "select item ,description as item_desc,Uf_pack ,unit_weight,Uf_TypeEnd ,Uf_Grade,Uf_thickness,Uf_od,Uf_Schedule FROM item_mst   "
                . "WHERE 1=1  $pCATE "
                . " $pGrade "
                . " $pNPS "
                . " $pSCHED ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);

        return $rs0;
    }

    function MatchItem() {
        $CATE = "";
        $GRADE = "";
        $NPS = "";
        $SCHED = "";
        $SPEC = "";
        $LEN = "";
        $this->_item_category ? $CATE = $this->_item_category : $CATE = "";
        $this->_Uf_grade ? $GRADE = $this->_Uf_grade : $GRADE = "";
        $this->_Uf_Nps ? $NPS = $this->_Uf_Nps : $NPS = "";
        $this->_Uf_sched ? $SCHED = $this->_Uf_sched : $SCHED = "";
        $this->_Uf_length ? $this->_Uf_length : $SPEC = "";
        isset($_POST["LEN"]) ? $LEN = $_POST["LEN"] : $LEN = "";
        if ($GRADE == "") {
            $pGrade = "";
        } else {
            $pGrade = " AND Uf_Grade = '$GRADE' ";
        }
        if ($NPS == "") {
            $pNPS = "";
        } else {
            $pNPS = " AND Uf_Nps = '$NPS' ";
        }
        if ($SCHED == "") {
            $pSCHED = "";
        } else {
            $pSCHED = " AND Uf_Schedule = '$SCHED' ";
        }
        if ($CATE == "") {
            $pCATE = "";
        } else {
            $pCATE = " AND Uf_TypeEnd ='$CATE' ";
        }
        if ($SPEC == "") {
            $pSpec = "";
        } else {
            $pSpec = " AND Uf_Spec = '$SPEC' ";
        }
        // not include search //
        if ($LEN == "") {
            $pLEN = "";
        } else {
            $pLEN = " AND Uf_lenght = '$LEN' ";
        }
        //================//
        $str_query = "select item ,description as item_desc,Uf_pack ,unit_weight,Uf_TypeEnd ,Uf_Grade,Uf_thickness,Uf_od,Uf_Schedule,Uf_spec,Uf_NPS FROM item_mst  WHERE 1=1 ";
        $query = array();
        $query[1] = $str_query . " $pSpec  $pCATE $pSCHED  $pNPS ";
        $query[2] = $str_query . " $pSpec  $pCATE $pSCHED   ";
        $query[3] = $str_query . " $pSpec  $pCATE $pNPS";
        $query[4] = $str_query . " $pSpec  $pCATE ";
        $query[5] = $str_query . " $pSpec  $pSCHED $pNPS ";
        $query[6] = $str_query . " $pSpec  $pNPS ";
        $query[7] = $str_query . " $pSpec   ";
        $query[8] = $str_query . " $pCATE $pSCHED $pNPS";
        $query[9] = $str_query . " $pCATE $pSCHED ";
        $query[10] = $str_query . " $pCATE $pNPS ";
        $query[11] = $str_query . " $pCATE ";
        $query[12] = $str_query . " $pSCHED ";
        $query[13] = $str_query . " $pNPS ";

        $result = array();
        $flagNotFound = 0;
        $result = array();
        $resultCountRow = array();
        for ($i = 1; $i <= count($query); $i++) {
            $tmpResult = null;
            $cSql = new SqlSrv();
            $tmpResult = $cSql->SqlQuery($this->StrConn, $query[$i]);
            array_splice($tmpResult, count($tmpResult) - 1, 1);
            if (count($tmpResult) > 0) {
                break;
            }
            $cSql = null;
        }
        return $tmpResult;
    }

    function GetItemHR($Thick_Lot,$Width_Lot) {
        $Locs = array();
        $Locs = $this->_locations;
//        print_r($Locs);
//        $Loc = $this->_location;
        $thickness = $this->_rpt_thick;
        $width = $this->_width;
        $cb = $this->_cb;
        $StartDate = $this->_StartDate;
        $ToDate = $this->_ToDate;
        $CriteriaDate = "";
        if ($cb == "1") {
            $CriteriaDate .= " AND (create_date BETWEEN '$StartDate'  AND '$ToDate'  ) ";
        }
        $query = "select  * from  V_WebApp_ItemLotLoc "
                . "WHERE 1=1 and qty_on_hand > 1 "
                . "AND (item_width like '%$width%' )   "
                . "AND ( item_code like '%rcr%' or item_code like '%rhr%' or item_code like '%rgi%'   )  $CriteriaDate ";
        if ($thickness != "") {
            $query = $query . " AND UF_thickness ='$thickness' ";
        }
        if ($Thick_Lot != "") {
            $query = $query . " AND lot_Uf_thickness ='$Thick_Lot' ";
        }
        if ($Width_Lot != "") {
            $query = $query . " AND lot_Uf_width ='$Width_Lot' ";
        }
//        if (!in_array("ALL", $Locs)) {
        if (count($Locs) == 1) {
            if (isset($Locs[0])) {
                if (strpos($Locs[0], 'ALL') !== false) {
                    $ep = explode("_", $Locs[0]);
                    $query = $query . "AND ( loc_desc like '%" . $ep[1] . "%' ) ";
                } else {
                    $query = $query . "AND ( loc = '" . $Locs[0] . "' ) ";
                }
            }
        } else {
            $AllLocs = "";
            foreach ($Locs as $ii => $rr) {
                if (strpos($rr, 'ALL') !== false) {
                    $ep = explode("_", $rr);
                    $AllLocs = $AllLocs . "loc_desc like '%" . $ep[1] . "%' OR ";
                } else {
                    $AllLocs = $AllLocs . "loc = '$rr' OR ";
                }
            }
            $AllLocs = substr($AllLocs, 0, -3);
            if (isset($Locs[0])) {
                $query = $query . " AND ($AllLocs) ";
            }
        }

//        }
//        return $query;
        $query .= " ORDER BY item_code, serial_no,item_width ASC";
        $cSql = new SqlSrv();

        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetItem_Thickness_List() {
        $thick = $this->_thick;
        $width = $this->_width;

        $query = "select uf_thickness from item_mst  "
                . "WHERE (item like '%rcr%' or item like '%rhr%' or item like '%rgi%'   )   "
                . "AND  (Uf_Thickness <> '' ) "
                . "AND (Uf_Thickness LIKE '%$thick%') AND (Uf_width like '%$width%')  group by uf_thickness  order by uf_thickness  ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetYearData() {

        $q = "SELECT year(CreateDate) as yr  FROM V_WebApp_ItemLoc_selling    Group by Year(CreateDate)";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $q);
        array_splice($rs0, count($rs0) - 1, 1);


        return $rs0;
    }

    function GetAllProductCode() {
        $start_date = $this->_StartDate;
        $end_date = $this->_ToDate;

        $callSP = 'Exec SP_WebApp_ReportItemSaleResult_IN @start_date=?,@end_date=?';
        $params = array($start_date, $end_date);
        // $result = sqlsrv_query($cnct,$stmt,$params) ;

        $stmt = sqlsrv_query($this->StrConn, $callSP, $params);
        if ($stmt === false) {
            echo "Error in executing statement 3.\n";
            die(print_r(sqlsrv_errors(), true));
        }
        $ArrProd = array();
        $ArrProd2 = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $ArrProd2["item_group"] = $row['item_group'];
            $ArrProd2["item_group_description"] = $row['item_group_description'];
            $ArrProd2["qty_ton"] = $row['qty_ton'];
            $ArrProd2["total_price"] = $row['total_price'];

            array_push($ArrProd, $ArrProd2);
        }
        sqlsrv_free_stmt($stmt);
        return $ArrProd;
    }

    function GetItemLocation() {
        $Locs = array();
        $Locs = $this->_locations;
        $atYear = $this->_atYear;
        $crYearSelling = "";
        $crYearShipping = "";
        $GetSize = $this->_rpt_size;
        $CriteriaSize_1_Digit = "";




        if ($atYear != "") {
            $crYearSelling = " AND Year(CreateDate) = $atYear ";
            $crYearShipping = " AND Year(pickup_date) = $atYear ";
        }
        $query = "select distinct item_code , item_desc , item_nps ,item_width,item_length  ,item_thick ,item_pack ,item_weight ,
            (SELECT       ISNULL( SUM(qty_on_hand),0)  AS sum_qty_soh
                          FROM    dbo.itemloc_mst
                          WHERE  (item = V_WebApp_ItemLoc_Sale.item_code)) AS sum_qty_oh,
            (SELECT       ISNULL(SUM(qty_ordered - qty_shipped),0) AS sum_qty_saling
                          FROM    dbo.V_WebApp_ItemLoc_selling
                          WHERE   1=1  $crYearSelling   AND (item = V_WebApp_ItemLoc_Sale.item_code) AND (stat = 'O')) AS sum_qty_saling,
            (SELECT       ISNULL( SUM(qty_shipped),0) AS sum_qty_shiping
                          FROM    dbo.V_WebApp_Item_ship_route
                          WHERE  1=1   $crYearShipping  AND   (item_code =V_WebApp_ItemLoc_Sale.item_code) and (do_seq is null or do_seq ='' ) and (do_line <> '' ) ) AS sum_qty_shipping ,
            (SELECT       ISNULL( SUM(qty_shipped),0) AS sum_qty_pending
                          FROM    dbo.V_WebApp_Item_ship_route
                          WHERE  1=1   $crYearShipping  AND   (item_code =V_WebApp_ItemLoc_Sale.item_code) and (do_seq is null or do_seq ='' ) and (do_line is null or do_line ='' ) ) AS sum_qty_pending
            FROM V_WebApp_ItemLoc_Sale
            WHERE 1=1  $CriteriaSize_1_Digit ";
        if ($this->_rpt_item != "") {
            $query = $query . "AND ( item_code + item_desc  LIKE '%" . trim($this->_rpt_item) . "%' ) ";
        }
        if ($GetSize) {
            if (strlen($GetSize) == 1) {
                $query .= " AND item_nps is not null  "
                        . "and  item_nps not like '%OD%' "
                        . "and  item_nps not like '%x%' "
                        . "and  item_nps not like '%''%' "
                        . "and  item_nps not like '%/%' "
                        . "AND  item_nps = '$GetSize'  ";
                $query .= " ";
            } else {
                if (stripos($GetSize, "/") !== false) {
                    if ((stripos($GetSize, " ") !== false)) {
                        $query .= " AND item_nps is not null  "
                                . "and  item_nps not like '%OD%' "
                                //  . "and  item_nps not like '%x%' "
                                . "and  item_nps not like '%''%' "
                                . "AND  item_nps = '$GetSize'   ";
                    } else if ((stripos($GetSize, "-") !== false)) {
                        $query .= " AND item_nps is not null  "
                                . "and  item_nps not like '%OD%' "
                                . "AND  item_nps like '%$GetSize%'   ";
                    } else {
                        $query .= " AND item_nps is not null  "
                                . "and  item_nps not like '%OD%' "
                                //     . "and  item_nps not like '%x%' "
                                . "and  item_nps not like '%''%' "
                                . "AND  item_nps not like '% %'    "
                                . "AND  item_nps = '$GetSize'   ";
                    }
                } else {

                    $query .= " AND item_nps is not null  "
                            . "AND  item_nps = '$GetSize'   ";
                }
            }
        }
//    $query .= "AND ( item_nps LIKE '%" . trim($this->_rpt_size) . "%' ) ";
//        if ($this->_rpt_size != "") {
//            
//        }
        if ($this->_rpt_thick != "") {
            if (strpos($this->_rpt_thick, '-') !== false) {
                $thickExplode = explode("-", $this->_rpt_thick);
                $query = $query . "AND ( ISNULL(CAST(item_thick as decimal(15,5)),0)  BETWEEN   " . $thickExplode[0] . " AND  " . $thickExplode[1] . " ) ";
            } else {
                $query = $query . "AND ( ISNULL(CAST(item_thick as decimal(15,5)),0)  =   " . $this->_rpt_thick . "  ) ";
            }
        }
        if ($this->_rpt_width != "") {
            if (strpos($this->_rpt_width, '-') !== false) {
                $widthExplode = explode("-", $this->_rpt_width);
                $query = $query . "AND ( ISNULL(CAST(item_width as decimal(15,5)),0)  BETWEEN   " . $widthExplode[0] . " AND  " . $widthExplode[1] . " ) ";
            } else {

                $query = $query . "AND ( ISNULL(CAST(item_width as decimal(15,5)),0)  BETWEEN 0 AND  " . trim($this->_rpt_width) . "  ) ";
            }
        }

        if ($this->_rpt_stock == "1") {
            $query = $query . " AND ( SELECT SUM(qty_on_hand)  FROM  dbo.itemloc_mst WHERE  (item = V_WebApp_ItemLoc_Sale.item_code ))  > 0  ";
        } else if ($this->_rpt_stock == "2") {
            $query = $query . " AND ( SELECT SUM(qty_on_hand)  FROM  dbo.itemloc_mst WHERE  (item = V_WebApp_ItemLoc_Sale.item_code ))  = 0 ";
        }
        $query = $query . " group by item_code , item_desc , item_nps ,item_width,item_length  ,item_thick ,item_pack ,item_weight   ";
//        if (!in_array("ALL", $Locs)) {
//            if (count($Locs) == 1) {
//                $query = $query . "AND ( loc = '" . $Locs[0] . "' ) ";
//            } else {
//                $AllLocs = "";
//
//                foreach ($Locs as $ii => $rr) {
//                    $AllLocs = $AllLocs . "loc = '$rr' OR ";
//                }
//                $AllLocs = substr($AllLocs, 0, -3);
//                $query = $query . "AND ($AllLocs) ";
//            }
//        }
//        if ($this->_location != "0") {
//            $query = $query . "AND ( loc = '" . trim($this->_location) . "' ) ";
//        }

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetItemBigLocation() {
        $Locs = array();
        $Locs = $this->_locations;
        $query = "select distinct item_code , item_desc , item_nps ,item_width,item_length  ,item_thick ,item_pack ,item_weight ,
            (SELECT       ISNULL( SUM(qty_on_hand),0)  AS sum_qty_soh
                          FROM    dbo.itemloc_mst
                          WHERE  (item = V_WebApp_ItemLoc_Sale.item_code)) AS sum_qty_oh,
            (SELECT       ISNULL(SUM(qty_ordered - qty_shipped),0) AS sum_qty_saling
                          FROM    dbo.V_WebApp_ItemLoc_selling
                          WHERE   1=1 AND (item = V_WebApp_ItemLoc_Sale.item_code) AND (stat = 'O')) AS sum_qty_saling,
            (SELECT       ISNULL( SUM(qty_shipped),0) AS sum_qty_shiping
                          FROM    dbo.V_WebApp_Item_ship_route
                          WHERE  1=1  AND   (item_code =V_WebApp_ItemLoc_Sale.item_code) and (do_seq is null or do_seq ='' ) and (do_line <> '' ) ) AS sum_qty_shipping
            from V_WebApp_ItemLoc WHERE 1=1 "
                . " ";
        if ($this->_rpt_item != "") {
            $query = $query . "AND ( CONCAT(item_code , ' ' , item_desc)  LIKE '%" . trim($this->_rpt_item) . "%' ) ";
        }
        if ($this->_rpt_size != "") {
            $query = $query . "AND ( item_size LIKE '%" . trim($this->_rpt_size) . "%' ) ";
        }
        if ($this->_rpt_thick != "") {
            if (strpos($this->_rpt_thick, '-') !== false) {
                $thickExplode = explode("-", $this->_rpt_thick);
                $query = $query . "AND ( ISNULL(CAST(item_thick_conv as decimal(15,5)),0)  BETWEEN   " . $thickExplode[0] . " AND  " . $thickExplode[1] . " ) ";
            } else {
                $query = $query . "AND ( ISNULL(CAST(item_thick_conv as decimal(15,5)),0)  =   " . $this->_rpt_thick . "  ) ";
            }
        }
        if ($this->_rpt_width != "") {
            if (strpos($this->_rpt_width, '-') !== false) {
                $widthExplode = explode("-", $this->_rpt_width);
                $query = $query . "AND ( ISNULL(CAST(item_width as decimal(15,5)),0)  BETWEEN   " . $widthExplode[0] . " AND  " . $widthExplode[1] . " ) ";
            } else {

                $query = $query . "AND ( ISNULL(CAST(item_width as decimal(15,5)),0)  BETWEEN 0 AND  " . trim($this->_rpt_width) . "  ) ";
            }
        }

        if ($this->_rpt_stock == "1") {
            $query = $query . " AND qty_oh > 0  ";
        } else if ($this->_rpt_stock == "2") {
            $query = $query . " AND qty_oh = 0 ";
        }

//        if (!in_array("ALL", $Locs)) {
        if (count($Locs) >= 1) {
            $AllLocs = "";
            foreach ($Locs as $ii => $rr) {
                $AllLocs = $AllLocs . "remark_wh = '$rr' OR ";
            }
            $AllLocs = substr($AllLocs, 0, -3);
            $query = $query . "AND ($AllLocs) ";
        }
//        }
//        if ($this->_location != "0") {
//            $query = $query . "AND ( loc = '" . trim($this->_location) . "' ) ";
//        }

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetItemDO() {

        $start_shipdate = $this->_start_shipdate;
        $end_shipdate = $this->_end_shipdate;
        $query = "SELECT  * FROM V_WebApp_ItemReportDO WHERE (item_code <> '' ) AND ( ship_date BETWEEN '$start_shipdate' AND '$end_shipdate' ) ";
        if ($this->_rpt_item != "") {
            $query = $query . "AND ( CONCAT(item_code , ' ' , item_desc)  LIKE '%" . trim($this->_rpt_item) . "%' ) ";
        }

        if ($this->_rpt_size != "") {
            $query = $query . "AND ( item_size LIKE '%" . trim($this->_rpt_size) . "%' ) ";
        }
        if ($this->_rpt_thick != "") {
            if (strpos($this->_rpt_thick, '-') !== false) {
                $thickExplode = explode("-", $this->_rpt_thick);
                $query = $query . "AND ( ISNULL(CAST(item_thick as decimal(15,5)),0)  BETWEEN   " . $thickExplode[0] . " AND  " . $thickExplode[1] . " ) ";
            } else {
                $query = $query . "AND ( ISNULL(CAST(item_thick as decimal(15,5)),0)  =   " . $this->_rpt_thick . "  ) ";
            }
        }
        if ($this->_rpt_width != "") {
            if (strpos($this->_rpt_width, '-') !== false) {
                $widthExplode = explode("-", $this->_rpt_width);
                $query = $query . "AND ( ISNULL(CAST(item_width as decimal(15,5)),0)  BETWEEN   " . $widthExplode[0] . " AND  " . $widthExplode[1] . " ) ";
            } else {

                $query = $query . "AND ( ISNULL(CAST(item_width as decimal(15,5)),0)  BETWEEN 0 AND  " . trim($this->_rpt_width) . "  ) ";
            }
        }
        $query = $query . " ORDER BY ship_date asc";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetNewInventoryMovement($QtyEnter,$Dst_Item,$Dst_Date,$TransTypeRun) {
        $callSP = 'EXEC [dbo].[MV_NewInventoryMovement] @QtyEnter=?,@Dst_Item=?,@Dst_Date=?,@TransTypeRun=?';
        $params = array($QtyEnter,$Dst_Item,$Dst_Date,$TransTypeRun);

        $stmt = sqlsrv_query($this->StrConn, $callSP, $params);
        if ($stmt === false) {
            echo "Error in executing statement 3.\n";
            die(print_r(sqlsrv_errors(), true));
        }
        $ArrProd = array();
        $ArrProd2 = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $ArrProd2["trans_date"] = $row['trans_date'];
            $ArrProd2["item"] = $row['item'];
            $ArrProd2["trans_type"] = $row['trans_type'];
            $ArrProd2["ref_type"] = $row['ref_type'];
            $ArrProd2["enter_qty"] = $row['enter_qty'];
            $ArrProd2["cur_qty"] = $row['cur_qty'];
            $ArrProd2["cur_cost"] = $row['cur_cost'];
            $ArrProd2["adj_qty"] = $row['adj_qty'];
            $ArrProd2["adj_amount"] = $row['adj_amt'];
            array_push($ArrProd, $ArrProd2);
        }
        sqlsrv_free_stmt($stmt);
        return $ArrProd;
    }

}
