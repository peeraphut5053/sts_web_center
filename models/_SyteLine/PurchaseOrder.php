<?php

class PurchaseOrder {

    var $StrConn = "";
    public $_Year = "";
    public $_Month = "";
    public $_Saleside = "";    // public $_StartDate = "";
    // public $_EndDate = "";
    // public $_Acct = array();
    public $year = "";
    public $month = "";
    public $saleside = "";
    public $_Type = "";
    public $_Vend_num = "";
    public $_item_group = "";

    function setConn($c) {
        $this->StrConn = $c;
    }

    Function GetRows_SP() {

        $year = $this->year;
        $month = $this->month;
        $saleside = $this->saleside;
        $callSP = 'Exec SP_WebApp_RawMatPO @year=?,@month=?,@saleside=?';
        $params = array($year, $month, $saleside);
        $stmt = sqlsrv_query($this->StrConn, $callSP, $params);
        if ($stmt === false) {
            return "Error in executing statement 3.\n";
            die(print_r(sqlsrv_errors(), true));
        }
        $ArrLG = array();
        $ArrLG2 = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $ArrLG2["vend_num"] = $row['vend_num'];
            $ArrLG2["vend_name"] = $row['vend_name'];
            $ArrLG2["item_group"] = $row['item_group'];
            $ArrLG2["total_amount"] = $row['total_amount'];
            array_push($ArrLG, $ArrLG2);
        }
        sqlsrv_free_stmt($stmt);
        return $ArrLG;
    }

    Function GetRows_SP_RPT_MAT_PURCHASE() {

//        $Accts ="" ;
//        foreach($Acct as $ii=>$rr ){
//            $Accts=$Accts.$rr.",";
//        }

        $Saleside = $this->_Saleside;
        $Year = $this->_Year;
        $Month = $this->_Month;
        $Type = $this->_Type;
        $Vend_num = $this->_Vend_num;
        $item_group = $this->_item_group;
        $callSP = 'Exec SP_WebApp_RawMatPO @year=?,@month=?,@Saleside=?,@type=?,@vend_num=?,@item_group=?';
        $params = array($Year, $Month, $Saleside, $Type, $Vend_num, $item_group);
        $stmt = sqlsrv_query($this->StrConn, $callSP, $params);
        if ($stmt === false) {
            return "Error in executing statement 3.\n";
            die(print_r(sqlsrv_errors(), true));
        }
        $ArrLG = array();
        $ArrLG2 = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $ArrLG2["vend_num"] = $row['vend_num'];
            $ArrLG2["vend_name"] = $row['vend_name'];
            $ArrLG2["item_group"] = $row['item_group'];
            if ($Type != "Detail") {
                $ArrLG2["total_amount"] = $row['total_amount'];
                $ArrLG2["total_kg"] = $row['total_kg'];
            } else {
                $ArrLG2["item"] = $row['item'];
                $ArrLG2["po_num"] = $row['po_num'];
                $ArrLG2["voucher"] = $row['voucher'];
                $ArrLG2["qty_kg"] = $row['qty_kg'];
                $ArrLG2["money"] = $row['money'];
            }

            array_push($ArrLG, $ArrLG2);
        }
        sqlsrv_free_stmt($stmt);
        return $ArrLG;
    }

    function GetReportPurchaseBySupplier($supplier, $from_date, $to_date, $item)
    {
        $query = "EXEC [dbo].[MV_PURCHASE_ORDER_REPORT_BY_PO_DATE_BY_ITEM_BY_VENDOR]
          @TransactionDateStarting = N'$from_date',
          @TransactionDateEnding = N'$to_date',
          @ItemStarting = " . ($item == "" ? "NULL" : "'" . $item . "'") . ",
          @ItemEnding = " . ($item == "" ? "NULL" : "'" . $item . "'") . ",
          @POType = NULL,
          @POStatus = NULL,
          @POLINEStatus = NULL,
          @pStartPoNum = NULL,
          @pEndPoNum = NULL,
          @pStartvendor = N'$supplier',
          @pEndVendor = N'$supplier'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetSupplierList()
    {
        $query = "SELECT    vend_num, name
FROM       vendaddr_mst 
where (vend_num like 'IM%' or vend_num like 'TH%')
  and name not like '%ยกเลิก%'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetItemPurchaseList()
    {
        $query = "select distinct item, [description] 
from poitem_mst where item is not null
order by item";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetReportPurchaseByAll($from_date, $to_date)
    {
        $query = "EXEC [dbo].[MV_PURCHASE_ORDER_REPORT_BY_PO_DATE_BY_ITEM_BY_VENDOR_SUM]
  @TransactionDateStarting = N'$from_date',
  @TransactionDateEnding = N'$to_date'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetPoCompareList($StartDate, $EndDate, $PoNum = '', $Vend = '', $FileStatus = 'ALL', $PrNum = '')
    {
        $where = array();
        $params = array();

        if (!empty($StartDate) && !empty($EndDate)) {
            $where[] = "po.order_date BETWEEN ? AND ?";
            $params[] = $StartDate . ' 00:00:00';
            $params[] = $EndDate . ' 23:59:59';
        } else if (!empty($StartDate)) {
            $where[] = "po.order_date >= ?";
            $params[] = $StartDate . ' 00:00:00';
        } else if (!empty($EndDate)) {
            $where[] = "po.order_date <= ?";
            $params[] = $EndDate . ' 23:59:59';
        }

        if (!empty($PoNum)) {
            $where[] = "po.po_num LIKE ?";
            $params[] = "%" . $PoNum . "%";
        }

        if (!empty($Vend)) {
            $where[] = "(po.vend_num LIKE ? OR ven.name LIKE ?)";
            $params[] = "%" . $Vend . "%";
            $params[] = "%" . $Vend . "%";
        }

        if (!empty($PrNum)) {
            $where[] = "EXISTS (SELECT 1 FROM poitem_mst poi_f WHERE poi_f.po_num = po.po_num AND poi_f.req_num LIKE ?)";
            $params[] = "%" . $PrNum . "%";
        }

        if ($FileStatus == 'HAS_FILE') {
            $where[] = "(pic.path IS NOT NULL AND pic.path <> '')";
        } else if ($FileStatus == 'NO_FILE') {
            $where[] = "(pic.path IS NULL OR pic.path = '')";
        }

        $sqlWhere = (count($where) > 0) ? " WHERE " . implode(" AND ", $where) : "";

        $query = "SELECT po.po_num,
                         order_date = CONVERT(VARCHAR(10), po.order_date, 120),
                         po.vend_num,
                         ven.name AS vend_name,
                         pr_num = (SELECT TOP 1 poi.req_num FROM poitem_mst poi WHERE poi.po_num = po.po_num),
                         pic.path AS file_path,
                         upload_date = CONVERT(VARCHAR(19), pic.createdate, 120),
                         pic.[user] AS upload_user
                  FROM po_mst po
                  LEFT JOIN vendaddr_mst ven ON ven.vend_num = po.vend_num
                  LEFT JOIN STS_po_pic pic ON pic.po_num = po.po_num
                  $sqlWhere
                  ORDER BY po.po_num DESC";

        $stmt = sqlsrv_query($this->StrConn, $query, $params);
        $result = array();
        if ($stmt === false) {
            return $result;
        }
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $result[] = $row;
        }
        sqlsrv_free_stmt($stmt);
        return $result;
    }

    function SavePoPic($po_num, $filename, $user = '')
    {
        $sqlCheck = "SELECT COUNT(*) as cnt FROM STS_po_pic WHERE po_num = ?";
        $stmtCheck = sqlsrv_query($this->StrConn, $sqlCheck, array($po_num));
        $exists = false;
        if ($stmtCheck && $rowCheck = sqlsrv_fetch_array($stmtCheck, SQLSRV_FETCH_ASSOC)) {
            $exists = ($rowCheck['cnt'] > 0);
        }
        if ($exists) {
            $sql = "UPDATE STS_po_pic SET [path] = ?, createdate = GETDATE(), [user] = ? WHERE po_num = ?";
            $params = array($filename, $user, $po_num);
        } else {
            $sql = "INSERT INTO STS_po_pic (po_num, [path], createdate, [user]) VALUES (?, ?, GETDATE(), ?)";
            $params = array($po_num, $filename, $user);
        }
        $stmt = sqlsrv_query($this->StrConn, $sql, $params);
        return ($stmt !== false);
    }

    function DeletePoPic($po_num)
    {
        $oldFile = "";
        $sqlGet = "SELECT [path] FROM STS_po_pic WHERE po_num = ?";
        $stmtGet = sqlsrv_query($this->StrConn, $sqlGet, array($po_num));
        if ($stmtGet && $rowGet = sqlsrv_fetch_array($stmtGet, SQLSRV_FETCH_ASSOC)) {
            $oldFile = $rowGet['path'];
        }
        $sql = "DELETE FROM STS_po_pic WHERE po_num = ?";
        $stmt = sqlsrv_query($this->StrConn, $sql, array($po_num));
        return array('success' => ($stmt !== false), 'file' => $oldFile);
    }

}

