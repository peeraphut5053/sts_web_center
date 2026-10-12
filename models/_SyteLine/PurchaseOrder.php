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
            $where[] = "(pic1.file_path1 IS NOT NULL OR pic2.file_path2 IS NOT NULL)";
        } else if ($FileStatus == 'NO_FILE') {
            $where[] = "(pic1.file_path1 IS NULL AND pic2.file_path2 IS NULL)";
        } else if ($FileStatus == 'HAS_BOTH') {
            $where[] = "(pic1.file_path1 IS NOT NULL AND pic2.file_path2 IS NOT NULL)";
        } else if ($FileStatus == 'HAS_ONE') {
            $where[] = "((pic1.file_path1 IS NOT NULL AND pic2.file_path2 IS NULL) OR (pic1.file_path1 IS NULL AND pic2.file_path2 IS NOT NULL))";
        }

        $sqlWhere = (count($where) > 0) ? " WHERE " . implode(" AND ", $where) : "";

        $query = "SELECT po.po_num,
                         order_date = CONVERT(VARCHAR(10), po.order_date, 120),
                         po.vend_num,
                         ven.name AS vend_name,
                         pr_num = (SELECT TOP 1 poi.req_num FROM poitem_mst poi WHERE poi.po_num = po.po_num),
                         pic1.file_path1,
                         pic1.upload_date1,
                         pic1.upload_user1,
                         pic2.file_path2,
                         pic2.upload_date2,
                         pic2.upload_user2,
                         file_path = COALESCE(pic1.file_path1, pic2.file_path2),
                         upload_date = COALESCE(pic1.upload_date1, pic2.upload_date2),
                         upload_user = COALESCE(pic1.upload_user1, pic2.upload_user2)
                  FROM po_mst po
                  LEFT JOIN vendaddr_mst ven ON ven.vend_num = po.vend_num
                  OUTER APPLY (
                      SELECT TOP 1 path AS file_path1, CONVERT(VARCHAR(19), createdate, 120) AS upload_date1, [user] AS upload_user1
                      FROM STS_po_pic p1
                      WHERE p1.po_num = po.po_num AND p1.path NOT LIKE po.po_num + '_2_%'
                      ORDER BY p1.createdate DESC
                  ) pic1
                  OUTER APPLY (
                      SELECT TOP 1 path AS file_path2, CONVERT(VARCHAR(19), createdate, 120) AS upload_date2, [user] AS upload_user2
                      FROM STS_po_pic p2
                      WHERE p2.po_num = po.po_num AND p2.path LIKE po.po_num + '_2_%'
                      ORDER BY p2.createdate DESC
                  ) pic2
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

    function SavePoPic($po_num, $filename, $user = '', $file_seq = 1)
    {
        $clean_po = preg_replace('/[^A-Za-z0-9_-]/', '', $po_num);
        $oldFile = "";
        if ($file_seq == 2) {
            $sqlCheck = "SELECT TOP 1 [path] FROM STS_po_pic WHERE po_num = ? AND [path] LIKE ?";
            $stmtCheck = sqlsrv_query($this->StrConn, $sqlCheck, array($po_num, $clean_po . '_2_%'));
        } else {
            $sqlCheck = "SELECT TOP 1 [path] FROM STS_po_pic WHERE po_num = ? AND [path] NOT LIKE ?";
            $stmtCheck = sqlsrv_query($this->StrConn, $sqlCheck, array($po_num, $clean_po . '_2_%'));
        }

        if ($stmtCheck && $rowCheck = sqlsrv_fetch_array($stmtCheck, SQLSRV_FETCH_ASSOC)) {
            $oldFile = $rowCheck['path'];
        }

        if (!empty($oldFile)) {
            $sql = "UPDATE STS_po_pic SET [path] = ?, createdate = GETDATE(), [user] = ? WHERE po_num = ? AND [path] = ?";
            $params = array($filename, $user, $po_num, $oldFile);
            $stmt = sqlsrv_query($this->StrConn, $sql, $params);
        } else {
            $sql = "INSERT INTO STS_po_pic (po_num, [path], createdate, [user]) VALUES (?, ?, GETDATE(), ?)";
            $params = array($po_num, $filename, $user);
            $stmt = sqlsrv_query($this->StrConn, $sql, $params);
        }
        return ($stmt !== false);
    }

    function DeletePoPic($po_num, $filename = '')
    {
        $deletedFiles = array();
        if (!empty($filename)) {
            $sqlGet = "SELECT [path] FROM STS_po_pic WHERE po_num = ? AND [path] = ?";
            $stmtGet = sqlsrv_query($this->StrConn, $sqlGet, array($po_num, $filename));
            if ($stmtGet && $rowGet = sqlsrv_fetch_array($stmtGet, SQLSRV_FETCH_ASSOC)) {
                $deletedFiles[] = $rowGet['path'];
            }
            $sql = "DELETE FROM STS_po_pic WHERE po_num = ? AND [path] = ?";
            $stmt = sqlsrv_query($this->StrConn, $sql, array($po_num, $filename));
        } else {
            $sqlGet = "SELECT [path] FROM STS_po_pic WHERE po_num = ?";
            $stmtGet = sqlsrv_query($this->StrConn, $sqlGet, array($po_num));
            while ($stmtGet && $rowGet = sqlsrv_fetch_array($stmtGet, SQLSRV_FETCH_ASSOC)) {
                $deletedFiles[] = $rowGet['path'];
            }
            $sql = "DELETE FROM STS_po_pic WHERE po_num = ?";
            $stmt = sqlsrv_query($this->StrConn, $sql, array($po_num));
        }
        return array('success' => ($stmt !== false), 'files' => $deletedFiles, 'file' => (!empty($deletedFiles) ? $deletedFiles[0] : ''));
    }

}

