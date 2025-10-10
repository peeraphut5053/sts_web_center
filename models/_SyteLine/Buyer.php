<?php

class Buyer
{

    var $StrConn = "";
    public $_start_Jobdate = ""; //
    public $_end_Jobdate = ""; //
    public $_start_date = ""; //
    public $_end_date = ""; //
    public $_lot = ""; //
    public $_item = ""; //
    public $_location = ""; //
    public $_end_Lot = ""; //
    public $_ref_num = ""; //
    public $_trans_type = ""; //
    public $_job_type = ""; //
    public $_u_m = ""; //
    public $_w_c = ""; //

    function setConn($c)
    {
        $this->StrConn = $c;
    }

    function RPT_BUYER_METERIAL($txtFromDate, $txtToDate, $item, $DepartmentName)
    {
        $searchDepartmentName = "";
        if ($item == "") {
            $searchItem = "";
        } else {
            $searchItem = "and mat.item like '%$item%'";
        }
        if ($DepartmentName !== "") {
            $searchDepartmentName = "and reason_mst.description like '%$DepartmentName%' ";
        }

        $query = "";
        $query = $query . " select distinct convert(date,mat.trans_date) as trans_date , trans_type_mst.trans_description, mat.item, item_mst.description as item_description, abs(mat.qty) as qty,mat.cost, mat.loc, loc.description as location_name ,mat.reason_code as department, reason_mst.description as department_name , mat.createdby , mat.document_num , mat.emp_num, emp.name as emp_name from matltran_mst mat left join item_mst on mat.item = item_mst.item left join location_mst loc on mat.loc = loc.loc left join reason_mst on mat.reason_code = reason_mst.reason_code "
            . " left join employee_mst emp on emp.emp_num = mat.emp_num "
            . " left join trans_type_mst on mat.trans_type = trans_type_mst.trans_type "
            . "where 1=1 "
            . $searchItem
            . $searchDepartmentName
            . " and (mat.trans_type = 'G' or (mat.trans_type = 'M' and qty > 0)) and (mat.item like 'ST%' or mat.item like 'RCA%' or mat.item like 'RCB%' or mat.item like 'RZI%')  "
            . " and ( mat.trans_date between '$txtFromDate' and '$txtToDate') ";


        //and ( month(mat.trans_date) = '$month' and year(mat.trans_date) = '$year')


        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function Save_store_pass_hdr($item_out, $date_out, $po, $dept, $company, $car, $detail, $purpose, $user)
    {
        $year = date('y');
        $cSql = new SqlSrv();
        $sql = "SELECT TOP 1 doc_no FROM STS_store_pass_hdr WHERE doc_no LIKE 'A$year%' ORDER BY doc_no DESC";
        $cSql = new SqlSrv();
        $result = $cSql->SqlQuery($this->StrConn, $sql);
        array_splice($result, count($result) - 1, 1);

        if (count($result) > 0) {
            $row = $result[0];
            $lastDoc = $row['doc_no'];
            // ดึงเลขลำดับจากเลขเอกสารล่าสุด (A2400001 -> 00001)
            $lastNumber = intval(substr($lastDoc, -5));
            $newNumber = $lastNumber + 1;
        } else {
            // ถ้าไม่มีเลขของปีนี้ เริ่มที่ 1
            $newNumber = 1;
        }
        // สร้างเลขเอกสารใหม่
        $docNumber = sprintf("A%s%05d", $year, $newNumber);
        $query = "INSERT INTO STS_store_pass_hdr (doc_no,item_out,date_out,po,dept,company,car,detail,purpose,[user]) OUTPUT inserted.* VALUES ('$docNumber','$item_out','$date_out','$po','$dept','$company','$car','$detail', '$purpose','$user')";
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
    function Save_store_pass_line($doc_no, $item_in, $date_in, $remark, $user)
    {
        $query = "INSERT INTO STS_store_pass_line (doc_no,item_in,date_in,remark,[user]) VALUES ('$doc_no','$item_in','$date_in','$remark','$user')";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function Get_store_pass_hdr($StartDate, $EndDate, $doc_no, $purpose)
    {
        $where = "";

        if ($doc_no !== "") {
            $where = " and t1.doc_no = '$doc_no'";
        }

        if ($purpose !== "") {
            $where = $where . " and t1.purpose = '$purpose'";
        }

        if ($StartDate !== "") {
            $where = $where . " and t1.date_out between '$StartDate' and '$EndDate'";
        }

        $query = "SELECT *,t1.[user] as user_out, t2.[user] as user_in,t1.doc_no
FROM STS_store_pass_hdr AS t1
LEFT JOIN STS_store_pass_line AS t2 ON t1.doc_no = t2.doc_no where 1=1 $where";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function Get_store_pass_line($StartDate, $EndDate, $doc_no)
    {

        $where = "";
        if ($doc_no !== "") {
            $where = " and t1.doc_no = '$doc_no'";
        }

        if ($StartDate !== "") {
            $where = $where . " and t1.date_out between '$StartDate' and '$EndDate'";
        }
        $query = "SELECT *,t1.[user] as user_out, t2.[user] as user_in,t1.doc_no
FROM STS_store_pass_hdr AS t1
LEFT JOIN STS_store_pass_line AS t2 ON t1.doc_no = t2.doc_no  -- Replace 'some_column' with the actual column used for joining
WHERE t2.date_in IS NULL $where";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function SaveExtra_store_pass_line($doc_no, $data, $type)
    {
        $query = "UPDATE STS_store_pass_line SET $type = '$data' WHERE doc_no = '$doc_no'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
    function SaveExtra_store_pass_hdr($doc_no, $data, $type)
    {
        $query = "UPDATE STS_store_pass_hdr SET $type = '$data' WHERE doc_no = '$doc_no'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetDataReportPDF($doc_no)
    {
        $query = "SELECT *, h.doc_no as doc
FROM STS_store_pass_hdr h
LEFT JOIN STS_store_pass_line l ON h.doc_no = l.doc_no 
WHERE h.doc_no = '$doc_no'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function SaveApproval($doc_no, $data, $type, $userApprover, $reason)
    {
        $query = "UPDATE STS_store_pass_hdr SET $type = '$data', approver = '$userApprover', reasonAppr = '$reason' WHERE doc_no = '$doc_no'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetDept()
    {
        $query = "select wc,[description] from wc_mst 
where [description] not like '%ลบ%' and [description] not like '%ยกเลิก%' 
and [description] not like '%กลุ่ม%' and wc not like 'PM%' 
union 
select wc, [description] = '' from STS_repair_wc
order by wc";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetWcDest()
    {
        $query = "select distinct loc, [description] 
from location_mst";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetDocList(){
        $query = "SELECT * FROM STS_store_withdraw_hdr WHERE doc_no like 'W%' ORDER BY doc_no DESC";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }
    function GetDocListByApprove(){
        $query = "SELECT h.doc_no, h.userApprove, l.qty_rcvd, l.qty_stockOut FROM STS_store_withdraw_hdr h
        left join STS_store_withdraw_line l on h.doc_no = l.doc_no
         WHERE h.doc_no like 'W%' AND h.approver1 is not null and h.approver2 is not null ORDER BY h.doc_no DESC";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetWithdrawByDocNo($doc_no)
    {
        $query = "SELECT h.*, l.*, h.remark as remark_h, h.[user] as user_hdr, w.[description] as deptDesc, i.[description] as itemDesc, w2.[description] as wcDestDesc, i.u_m, u.[description] as unit FROM STS_store_withdraw_hdr h
        left join STS_store_withdraw_line l on h.doc_no = l.doc_no
        left join wc_mst w on h.dept = w.wc
        left join item_mst i on l.item = i.item
        left join wc_mst w2 on l.wc_dest = w2.wc
        left join unitcd1_mst u on l.wc_dest = u.unit1
        where h.doc_no = '$doc_no'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetItemList()
    {
        $query = "select itm.item,itm.[description],itm.u_m 
from item_mst itm inner join itemwhse_mst whse on whse.item = itm.item and qty_on_hand > 0
where (itm.item like 'ST%' or itm.item like 'RZI%' or itm.item like 'RC%')
and itm.[description] not like '%ยกเลิก%' order by itm.item";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetReasonCode(){
        $query = "select reason_code, [description], Acct = inv_adj_acct, AcctUnit1 = inv_adj_acct_unit1 
from reason_mst where reason_class = 'MISC ISSUE'";
        $cSql = new SqlSrv();
        $rs1 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs1, count($rs1) - 1, 1);
        $query2 = "select acct,[description] from chart_mst
where [description] not like '%ยกเลิก%'";
        $rs2 = $cSql->SqlQuery($this->StrConn, $query2);
        array_splice($rs2, count($rs2) - 1, 1);
        $query3 = "select unit1,[description] from unitcd1_mst
where [description] not like '%ยกเลิก%'";
        $rs3 = $cSql->SqlQuery($this->StrConn, $query3);
        array_splice($rs3, count($rs3) - 1, 1);
        $rs = [
            'reason' => $rs1,
            'acct' => $rs2,
            'unit1' => $rs3
        ];
        return $rs;
    }

    function CreateWithdraw($dept, $user, $remark_h, $arr_item, $arr_qty, $arr_wc_dest, $arr_remark)
    {
        if (sqlsrv_begin_transaction($this->StrConn) === false) {
            die("Transaction failed: " . print_r(sqlsrv_errors(), true));
        }

        try {
            $year = date('y');
            $month = date('m');
            $day = date('d');
            $prefix = "W{$year}{$month}{$day}";

            // วิธีที่ 1: ใช้ Table Lock (แนะนำ)
            $lockSql = "SELECT TOP 1 1 FROM STS_store_withdraw_hdr WITH (TABLOCKX)";
            $cSql = new SqlSrv();
            $cSql->SqlQuery($this->StrConn, $lockSql);

            // หรือวิธีที่ 2: Lock ด้วย Application Lock
            // $lockSql = "EXEC sp_getapplock @Resource='withdraw_doc_no', @LockMode='Exclusive', @LockTimeout=5000";
            // $cSql->SqlQuery($this->StrConn, $lockSql);

            // Query เลขล่าสุดหลัง lock แล้ว
            $sql = "SELECT TOP 1 doc_no 
                FROM STS_store_withdraw_hdr 
                WHERE doc_no LIKE '{$prefix}%' 
                ORDER BY doc_no DESC";

            $result = $cSql->SqlQuery($this->StrConn, $sql);
            array_splice($result, count($result) - 1, 1);

            if (count($result) > 0) {
                $lastDoc = $result[0]['doc_no'];
                $lastNumber = intval(substr($lastDoc, -3));
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            $docNumber = sprintf("W%s%s%s%03d", $year, $month, $day, $newNumber);

            // ใส่ Unique Constraint ที่ table
            // ALTER TABLE STS_store_withdraw_hdr ADD CONSTRAINT UQ_doc_no UNIQUE (doc_no)

            // Escape ข้อมูล
            $dept = str_replace("'", "''", $dept);
            $remark_h = str_replace("'", "''", $remark_h);
            $user = str_replace("'", "''", $user);

            // Insert header
            $query = "INSERT INTO STS_store_withdraw_hdr (doc_no, dept, remark, [user]) 
                  VALUES ('$docNumber', '$dept', '$remark_h', '$user')";
            $cSql->SqlQuery($this->StrConn, $query);

            // Insert lines
            foreach ($arr_item as $key => $item) {
                $seq = $key + 1;
                $item_escaped = str_replace("'", "''", $arr_item[$key]);
                $qty = floatval($arr_qty[$key]);
                $wc_dest = str_replace("'", "''", $arr_wc_dest[$key]);
                $remark_l = str_replace("'", "''", $arr_remark[$key]);

                $query2 = "INSERT INTO STS_store_withdraw_line 
                       (doc_no, line_id, item, qty_wd, wc_dest, remark) 
                       VALUES ('$docNumber', $seq, '$item_escaped', $qty, '$wc_dest', '$remark_l')";
                $cSql->SqlQuery($this->StrConn, $query2);
            }

            sqlsrv_commit($this->StrConn);
            return $docNumber;
        } catch (Exception $e) {
            sqlsrv_rollback($this->StrConn);
            throw $e;
        }
    }

    function UpdateWithdraw($doc_no, $dept, $wc, $user, $remark_h, $arr_item, $arr_qty, $arr_qty_rcvd, $arr_wc_dest, $arr_remark)
    {
        $query = "UPDATE STS_store_withdraw_hdr SET remark = '$remark_h' WHERE doc_no = '$doc_no'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        // foreach insert item act weight qty u_m
        foreach ($arr_item as $key => $item) {
            $qty = $arr_qty[$key];
            $qty_rcvd = $arr_qty_rcvd[$key];
            $wc_dest = $arr_wc_dest[$key];
            $remark_l = $arr_remark[$key];
            $query2 = "UPDATE STS_store_withdraw_line SET qty_rcvd = '$qty_rcvd' WHERE doc_no = '$doc_no' AND item = '$item' AND wc_dest = '$wc_dest'";
            $cSql = new SqlSrv();
            $rs = $cSql->SqlQuery($this->StrConn, $query2);
        }
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function ApproveOneWithdraw($doc_no, $approve)
    {
        $query = "UPDATE STS_store_withdraw_hdr SET approver1 = '$approve', updatedate = getdate()  WHERE doc_no = '$doc_no'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function ApproveTwoWithdraw($doc_no, $approve)
    {
        $query = "UPDATE STS_store_withdraw_hdr SET approver2 = '$approve', updatedate = getdate()   WHERE doc_no = '$doc_no'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function CancelApproveTwoWithdraw($doc_no, $approve)
    {
        $query = "UPDATE STS_store_withdraw_hdr SET approver2 = null, updatedate = getdate()   WHERE doc_no = '$doc_no'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function ApproveStockWithdraw($doc_no, $stock)
    {
        $query = "UPDATE STS_store_withdraw_hdr SET stock = '$stock', updatedate = getdate()  WHERE doc_no = '$doc_no'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function ApproveUserWithdraw($doc_no)
    {
        $query = "UPDATE STS_store_withdraw_hdr SET userApprove = 1, updatedate = getdate()  WHERE doc_no = '$doc_no'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function AddNewItemWithdraw($doc_no, $item, $qty, $wc_dest, $remark)
    {
        $cSql = new SqlSrv();
        $s = "SELECT TOP 1 line_id FROM STS_store_withdraw_line WHERE doc_no = '$doc_no' ORDER BY line_id DESC";
            $seq = $cSql->SqlQuery($this->StrConn, $s);
            array_splice($seq, count($seq) - 1, 1);

            if (count($seq) > 0) {
                $row = $seq[0];
                $lastSeq = $row['line_id'];
                $newNumber = $lastSeq + 1;
            } else {
                // ถ้าไม่มีเลขของปีนี้ เริ่มที่ 1
                $newNumber = 1;
            }
            $seq = $newNumber;
        $query = "INSERT INTO STS_store_withdraw_line (doc_no, line_id,item,qty_wd,wc_dest,remark) OUTPUT inserted.* VALUES ('$doc_no', '$seq', '$item', '$qty', '$wc_dest', '$remark')";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function UpdateWithdrawItem($doc_no,$line_id, $item, $qty, $wc_dest,  $remark)
    {
        $query = "UPDATE STS_store_withdraw_line SET item = '$item', qty_wd = '$qty', wc_dest = '$wc_dest', remark = '$remark', updatedate = getdate() WHERE doc_no = '$doc_no' AND line_id = '$line_id'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function SaveMiscIssue($doc_no, $item, $loc, $reason, $qty, $user, $line_id,$acct, $acct_unit1, $um)
    {
        $query = "EXEC [dbo].[STS_EnhanceItemMiscIssueSp]
  @_Item = '$item'
, @_Qty =  $qty
, @_UM = '$um'
, @_DocNum = '$doc_no'
, @_Whse = MAIN
, @_Loc = $loc
, @MiscReason = '$reason'
, @_Lot = NULL
, @Acct = '$acct'
, @AcctUnit1 = '$acct_unit1'
, @AcctUnit2 = NULL 
, @AcctUnit3 = NULL
, @AcctUnit4 = NULL
, @line_id = $line_id
, @pSite = STS
 ";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);

        $query2 = "SELECT h.*, l.*, h.remark as remark_h, h.[user] as user_hdr, w.[description] as deptDesc, i.[description] as itemDesc, w2.[description] as wcDestDesc, i.u_m FROM STS_store_withdraw_hdr h
        left join STS_store_withdraw_line l on h.doc_no = l.doc_no
        left join wc_mst w on h.dept = w.wc
        left join item_mst i on l.item = i.item
        left join wc_mst w2 on l.wc_dest = w2.wc
        where h.doc_no = '$doc_no'";
        $cSql = new SqlSrv();
        $rs2 = $cSql->SqlQuery($this->StrConn, $query2);
        array_splice($rs2, count($rs2) - 1, 1);

        return $rs2;
    }

    function SaveMiscIssueByLot($doc_no, $item, $lot, $qty, $fromLoc, $toLoc, $user, $line_id)
    {
        $query = "EXEC [dbo].[STS_MvPostSp_store_WEB]
  @Item     = '$item'
, @Lot    = '$lot'
, @FromLoc    = '$fromLoc'
, @ToLoc      = '$toLoc'
, @QTY        = $qty
, @DocumentNum     =  '$doc_no'
, @line_id    = $line_id
, @TagID   = NULL
, @pSite  = 'STS'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
 

        $query2 = "SELECT h.*, l.*, h.remark as remark_h, h.[user] as user_hdr, w.[description] as deptDesc, i.[description] as itemDesc, w2.[description] as wcDestDesc, i.u_m  FROM STS_store_withdraw_hdr h
        left join STS_store_withdraw_line l on h.doc_no = l.doc_no
        left join wc_mst w on h.dept = w.wc
        left join item_mst i on l.item = i.item
        left join wc_mst w2 on l.wc_dest = w2.wc
        where h.doc_no = '$doc_no'";
        $cSql = new SqlSrv();
        $rs2 = $cSql->SqlQuery($this->StrConn, $query2);
        array_splice($rs2, count($rs2) - 1, 1);

        return $rs2;
    }

    function UpdateQty_rcvd($doc_no, $item, $line_id, $val)
    {
        $query = "UPDATE STS_store_withdraw_line SET qty_rcvd = '$val', updatedate = getdate() WHERE doc_no = '$doc_no' AND item = '$item' AND line_id = '$line_id'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function UpdateQty_return($doc_no, $item, $line_id, $val)
    {
        $query = "UPDATE STS_store_withdraw_line SET [return] = '$val', updatedate = getdate() WHERE doc_no = '$doc_no' AND item = '$item' AND wc_dest = '$line_id'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }
    function UpdateWithdrawHeader($doc_no, $dept, $remark){
        $query = "UPDATE STS_store_withdraw_hdr SET dept = '$dept', remark = '$remark', updatedate = getdate() WHERE doc_no = '$doc_no'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetLot($item)
    {
        $query = "select item,lot,loc, qty_on_hand 
from lot_loc_mst
where item = '$item' and qty_on_hand <> 0";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetFromLoc($item, $lot)
    {
        $query = "select item,lot,loc, qty_on_hand 
from lot_loc_mst
where item = '$item' and lot = '$lot' and qty_on_hand <> 0";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetToLoc($item)
    {
        $query = "select item, loc 
from itemloc_mst
where item = '$item'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetToLocLot($wc): array
    {
        $query = "select top 1 wc, loc, [description] From location_mst
where wc = '$wc'
order by loc";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetLocItem($item)
    {
        $query = "select item, loc, qty_on_hand
from itemloc_mst
where item = '$item' and qty_on_hand <> 0";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetUM($item){
        $query = "select item,[description],u_m from item_mst where item = '$item'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function CheckExistLoc($item, $loc){
        $query = "select item, loc from itemloc_mst
where item = '$item' and loc = '$loc'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }
    
    function CheckStockComplete($doc_no, $item): array{
        $query = "select line.doc_no, line_id, line.qty_rcvd
from STS_store_withdraw_line line inner join matltran_mst mat  on mat.item = line.item and mat.document_num = line.doc_no
where  mat.trans_type = 'M' and mat.item = '$item' and mat.document_num = '$doc_no' and mat.qty > 0
group by line.doc_no, line_id, line.qty_rcvd
having  sum(mat.qty) < line.qty_rcvd";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetReportWithdraw($doc_no, $startDate, $endDate, $dept, $userApprove, $approve1,$approve2,$stock, $wc): array{
        $wh = '';

        if ($doc_no != "") {
            $wh = $wh . "and hdr.doc_no = '$doc_no' ";
        }

        if ($startDate != "" && $endDate != "") {
            $wh = $wh . "and convert(date, hdr.createdate) between '$startDate' and '$endDate' ";
        }

        if ($dept != "") {
            $wh = $wh . "and hdr.dept = '$dept' ";
        }

        if ($userApprove == "1") {
            $wh = $wh . "and hdr.userApprove = 1 ";
        } else if ($userApprove == "2") {
            $wh = $wh . "and hdr.userApprove is null ";
        }

        if ($approve1 == "1") {
            $wh = $wh . "and hdr.approver1 is not null ";
        } else if ($approve1 == "2") {
            $wh = $wh . "and hdr.approver1 is null ";
        }

        if ($approve2 == "1") {
            $wh = $wh . "and hdr.approver2 is not null ";
        } else if ($approve2 == "2") {
            $wh = $wh . "and hdr.approver2 is null ";
        }

        if ($stock == "1") {
            $wh = $wh . "and line.stockOUT = 1 ";
        } else if ($stock == "2") {
            $wh = $wh . "and line.stockOUT is null ";
        }

        if ($wc != "") {
            $wh = $wh . "and line.wc_dest = '$wc' ";
        }

        $query = "select hdr.doc_no, [date] = hdr.createdate
	  , hdr.dept
      , hdr.[user]
	  , นำไปใช้ = line.wc_dest
	  , line.item
	  , item.[description]
	  , item.u_m
	  , item_remark = line.remark
	  , hdr_remark = hdr.remark 
	  , hdr.userApprove
	  , hdr.approver1
	  , hdr.approver2
	  , ขอเบิก = line.qty_wd
	  , เบิกได้ = line.qty_rcvd
	  , ตัดสต๊อก = convert(decimal(10,2), qty_stockOUT)
from STS_store_withdraw_hdr hdr 
	inner join STS_store_withdraw_line line on hdr.doc_no = line.doc_no
	left join item_mst item on item.item = line.item
	left join wc_mst wc on wc.wc = line.wc_dest
where 1 = 1 $wh";

        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function DeleteItemWithdraw($doc_no, $line_id){
        $query = "delete from STS_store_withdraw_line where doc_no = '$doc_no' and line_id = '$line_id'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetAcct(){
        $query = "select acct,[description] from chart_mst
where [description] not like '%ยกเลิก%'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetAcctUnit(){
        $query = "select unit1,[description] from unitcd1_mst
where [description] not like '%ยกเลิก%'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }


    
}
