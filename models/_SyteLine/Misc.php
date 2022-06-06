<?php

class Misc {

    var $StrConn = "";
    // public $_StartDate = "";
    // public $_EndDate = "";
    // public $_Acct = array();
    public $spTransDate = "";
    public $spSite = "";
    public $spItem = "";
    public $spItem_UM = "";
    public $spWhse = "";
    public $spLoc = "";
    public $spLot = "";
    public $spDocNum = "";
    public $spQtyIssuse = 0;
    public $spReasonCode = "";
    public $spAcct = "";
    public $spIsNewLot = "";
    public $spQtyMove = 0;
    public $spLocFrom = "";
    public $spLotFrom = "";
    public $spLocTo = "";
    public $spLotTo = "";
    public $spTagId = "";

    function setConn($c) {
        //=============tmp test to cuztomize db =======
        $TmpConn = array(
            "Database" => 'Live_App_2003_ctm',
            "UID" => 'sa',
            "PWD" => 'Sts@2017',
            "MultipleActiveResultSets" => true,
            "CharacterSet" => 'utf-8');
        $TmpSetConn = sqlsrv_connect('sts-sldb', $TmpConn);
        //=============tmp test to cuztomize db =======


        $this->StrConn = $TmpSetConn;
    }

    Function GetWhse($Cond) {

        $cSql = new SqlSrv();
        $q = "SELECT  whse , name  FROM whse_mst  $Cond ";
        $rs = $cSql->SqlQuery($this->StrConn, $q);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function GetUm($Cond) {

        $cSql = new SqlSrv();
        $q = "SELECT  u_m  , description  FROM u_m_mst  $Cond ";
        $rs = $cSql->SqlQuery($this->StrConn, $q);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function GetReason($Cond) {

        $cSql = new SqlSrv();
        $q = "select 
                reason_class 
                , reason_code 
                , reason_mst.description  as reason_description
                , inv_adj_acct as acct 
                , chart_mst.description as acct_description 
                , inv_adj_acct_unit1 as acct_unit1 
                , inv_adj_acct_unit2 as acct_unit2 
                , inv_adj_acct_unit3 as acct_unit3 
                , inv_adj_acct_unit4 as acct_unit4 
                from reason_mst  
                left join chart_mst on reason_mst.inv_adj_acct = chart_mst.acct    $Cond  order by reason_code asc";
        $rs = $cSql->SqlQuery($this->StrConn, $q);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function GetChart($Cond) {

        $cSql = new SqlSrv();
        $q = "select acct , description ,access_unit1 , access_unit2 , access_unit3 ,access_unit4  from chart_mst     $Cond ";
        $rs = $cSql->SqlQuery($this->StrConn, $q);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function GetlocByItem($item) {

        $cSql = new SqlSrv();
        $q = "select item, loc,qty_on_hand as qty from  itemloc_mst where item =   '$item'  order by qty desc ";
        $rs = $cSql->SqlQuery($this->StrConn, $q);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function GetTagInfo($tag_id) {

        $cSql = new SqlSrv();
        $q = "select 
 
	id
	,tag.item
	,item.description
	,item.u_m as Um
	,tag.job
	,tag.lot as lot
        ,lotloc.loc as loc
	,qty1 as Qty
	,qty2  
	,isnull(lotloc.loc ,'') as loc
	from mv_bc_tag tag 
	left join item_mst item on tag.item = item.item 
	left join lot_loc_mst lotloc on tag.lot = lotloc.lot  
	where id='$tag_id'";

        $rs = $cSql->SqlQuery($this->StrConn, $q);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function GetlotByItem($item) {

        $cSql = new SqlSrv();
        //  $q = "select item , lot , rcvd_qty as qty  from  lot_mst where item=   '$item'  order by qty desc ";
        $q = "select item ,lot , qty_on_hand as qty from lot_loc_mst where item=   '$item'  order by qty desc ";

        $rs = $cSql->SqlQuery($this->StrConn, $q);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function GenNewLot() {
        $lot = "";
        $callSP = "EXEC	 STS_FetchNextLotSp "
                . "@pItem = ?  ,@pSite='' , @rLot = ? ";

        $params = array(
            array($this->spItem, SQLSRV_PARAM_IN),
            array(&$lot, SQLSRV_PARAM_INOUT)
        );


        $stmt = sqlsrv_query($this->StrConn, $callSP, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
//             print_r($this->StrConn);
            return "Error in executing statement.\n ";
        }
//        $InfoBar = "";
//        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
//            $InfoBar = $row['@Infobar'];
//        }


        return $lot;
    }

    Function GetlotByItemAndLoc($item, $loc) {

        $cSql = new SqlSrv();
        //  $q = "select item , lot , rcvd_qty as qty  from  lot_mst where item=   '$item'  order by qty desc ";
        $q = "select item ,lot , qty_on_hand as qty from lot_loc_mst where item=   '$item' and loc='$loc'  order by qty_on_hand desc ";

        $rs = $cSql->SqlQuery($this->StrConn, $q);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function STS_MiscIssueWeb_Sp() {



        $callSP = "EXEC	 STS_MiscIssueWeb_Sp "
                . "@TransDate = ? "
                . ", @Item = ? "
                . ", @Item_UM = ? "
                . ", @Whse = ? "
                . ", @Loc = ? "
                . ", @Lot = ? "
                . ", @DocNum = ? "
                . ", @QtyIssuse = ?  "
                . ", @ReasonCode =?  "
                . ", @Acct =  ? ";

        $params = array(
            $this->spTransDate
            , $this->spItem
            , $this->spItem_UM
            , $this->spWhse
            , $this->spLoc
            , $this->spLot
            , $this->spDocNum
            , $this->spQtyIssuse
            , $this->spReasonCode
            , $this->spAcct
        );

        $stmt = sqlsrv_query($this->StrConn, $callSP, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
//             print_r($this->StrConn);
            return "Error in executing statement.\n ";
        }
        $InfoBar = "";
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $InfoBar = $row['@Infobar'];
        }


        return $InfoBar;
    }

    function STS_MiscReceiptWeb_Sp() {
        $InfoBar = "";
        if ($this->spIsNewLot == "1") {
            $callSPBuildLot = "EXEC	 STS_BuildLot "
                    . "@pItem = ? "
                    . ", @pLot = ? "
                    . ", @pRcvdQty = ? ";
            $paramsBuildLot = array(
                $this->spItem
                , $this->spLot
                , $this->spQtyIssuse
            );
            $stmtBuildLot = sqlsrv_query($this->StrConn, $callSPBuildLot, $paramsBuildLot);
            if ($stmtBuildLot === false) {
                die(print_r(sqlsrv_errors(), true));

                return "Error in executing statement.\n ";
            }
            while ($rowBuildLot = sqlsrv_fetch_array($stmtBuildLot, SQLSRV_FETCH_ASSOC)) {
                $InfoBar = "Lot:" . $rowBuildLot['@Infobar'] . "#";
            }
        }




        $callSP = "EXEC	 STS_MiscReceiptWeb_Sp "
                . "@TransDate = ? "
                . ", @Item = ? "
                . ", @Item_UM = ? "
                . ", @Whse = ? "
                . ", @Loc = ? "
                . ", @Lot = ? "
                . ", @DocNum = ? "
                . ", @QtyReceipt = ?  "
                . ", @ReasonCode =?  "
                . ", @Acct =  ? ";

        $params = array(
            $this->spTransDate
            , $this->spItem
            , $this->spItem_UM
            , $this->spWhse
            , $this->spLoc
            , $this->spLot
            , $this->spDocNum
            , $this->spQtyIssuse
            , $this->spReasonCode
            , $this->spAcct
        );

        $stmt = sqlsrv_query($this->StrConn, $callSP, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
//             print_r($this->StrConn);
            return "Error in executing statement.\n ";
        }

        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $InfoBar .= " RECEIPT:" . $row['@Infobar'] . "#";
        }


        return $InfoBar;
    }

    function STS_MvPostSp_WEB() {
        $InfoBar = "";
 
            $callSP  = "EXEC STS_MvPostSp_WEB  "
                    . " @W_PDate = ?,
                        @W_PQty = ?,
                        @W_PItem = ?,
                        @W_FromWhse = ?,
                        @W_FromLoc = ?,
                        @W_FromLot = ?,
                        @W_ToWhse = ?,
                        @W_ToLoc =  ?,
                        @W_ToLot = ?,
                        @W_PZeroCost = 0,
                        @W_DocumentNum = ?,
                        @W_FromImportDocId = null,
                        @W_ToImportDocId = null,
                        @W_TagId = ? ";

            $params  = array(
                $this->spTransDate
                , $this->spQtyMove
                , $this->spItem
                , $this->spWhse
                , $this->spLocFrom
                , $this->spLotFrom
                , $this->spWhse
                , $this->spLocTo
                , $this->spLotTo
                , $this->spDocNum
                , $this->spTagId
            );
            print_r($params);
            $stmt  = sqlsrv_query($this->StrConn, $callSP, $params);
            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));

                return "Error in executing statement.\n ";
            }
            while ($row  = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $InfoBar =  $row['@Infobar']  ;
            }
       

 

        return $InfoBar;
    }

}
