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
}
