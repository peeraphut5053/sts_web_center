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

    function Save_store_pass_hdr($item_out, $date_out, $po, $dept, $company, $car, $detail, $purpose, $user){
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

    function GetDept(){
        $query = "
select wc,[description] from wc_mst 
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
    
    function GetDocList(){
        $query = "SELECT doc_no FROM STS_store_withdraw_hdr WHERE doc_no like 'W%' ORDER BY doc_no DESC";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetWithdrawByDocNo($doc_no){
        $query = "SELECT *, STS_store_withdraw_hdr.remark as remark_h FROM STS_store_withdraw_hdr
        left join STS_store_withdraw_line on STS_store_withdraw_hdr.doc_no = STS_store_withdraw_line.doc_no
        where STS_store_withdraw_hdr.doc_no = '$doc_no'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetItemList(){
        $query = "select item,[description] from item_mst where item like 'ST%' and [description] not like '%ยกเลิก%' order by item";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function CreateWithdraw($dept, $wc, $user, $remark_h, $arr_item, $arr_qty, $arr_wc_dest, $arr_remark){
        $year = date('y');
        $month = date('m');
        $cSql = new SqlSrv();
        $sql = "SELECT TOP 1 doc_no FROM STS_store_withdraw_hdr WHERE doc_no LIKE 'W$year$month%' ORDER BY doc_no DESC";
        $cSql = new SqlSrv();
        $result = $cSql->SqlQuery($this->StrConn, $sql);
        array_splice($result, count($result) - 1, 1);

        if (count($result) > 0) {
            $row = $result[0];
            $lastDoc = $row['doc_no'];
            // ดึงเลขลำดับจากเลขเอกสารล่าสุด (W2407001 -> 001)
            $lastNumber = intval(substr($lastDoc, -3));
            $newNumber = $lastNumber + 1;
        } else {
            // ถ้าไม่มีเลขของปีนี้ เริ่มที่ 1
            $newNumber = 1;
        }
        // สร้างเลขเอกสารใหม่
        $docNumber = sprintf("W%s%02d%03d", $year, $month, $newNumber);
        $query = "INSERT INTO STS_store_withdraw_hdr (doc_no,dept,wc,remark,[user]) VALUES ('$docNumber', '$dept', '$wc', '$remark_h', '$user')";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        // foreach insert item act weight qty u_m
        foreach ($arr_item as $key => $item) {
            $qty = $arr_qty[$key];
            $wc_dest = $arr_wc_dest[$key];
            $remark_l = $arr_remark[$key];
            $query2 = "INSERT INTO STS_store_withdraw_line (doc_no,item,qty_wd,wc_dest,remark) VALUES ('$docNumber', '$item', '$qty', '$wc_dest', '$remark_l')";
            $cSql = new SqlSrv();
            $rs = $cSql->SqlQuery($this->StrConn, $query2);
        }
        array_splice($rs, count($rs) - 1, 1);
        return $docNumber;
    }
    
    function UpdateWithdraw($doc_no,$dept, $wc, $user, $remark_h, $arr_item, $arr_qty,$arr_qty_rcvd, $arr_wc_dest, $arr_remark){
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

    function ApproveOneWithdraw($doc_no,$approve){
        $query = "UPDATE STS_store_withdraw_hdr SET approver1 = '$approve' WHERE doc_no = '$doc_no'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

     function ApproveTwoWithdraw($doc_no,$approve){
        $query = "UPDATE STS_store_withdraw_hdr SET approver2 = '$approve' WHERE doc_no = '$doc_no'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

     function ApproveStockWithdraw($doc_no,$stock){
        $query = "UPDATE STS_store_withdraw_hdr SET stock = '$stock' WHERE doc_no = '$doc_no'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

     function ApproveUserWithdraw($doc_no){
        $query = "UPDATE STS_store_withdraw_hdr SET userApprove = 1 WHERE doc_no = '$doc_no'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function AddNewItemWithdraw($doc_no, $item, $qty, $wc_dest, $remark){
        $query = "INSERT INTO STS_store_withdraw_line (doc_no,item,qty_wd,wc_dest,remark) VALUES ('$doc_no', '$item', '$qty', '$wc_dest', '$remark')";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function UpdateWithdrawItem($doc_no, $item_old, $item, $qty, $wc_dest, $wc_dest_old, $remark){
        $query = "UPDATE STS_store_withdraw_line SET item = '$item', qty_wd = '$qty', wc_dest = '$wc_dest', remark = '$remark' WHERE doc_no = '$doc_no' AND item = '$item_old' AND wc_dest = '$wc_dest_old'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }


}
