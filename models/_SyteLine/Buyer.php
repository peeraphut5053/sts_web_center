<?php

class Buyer {

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

    function setConn($c) {
        $this->StrConn = $c;
    }

    function RPT_BUYER_METERIAL($txtFromDate, $txtToDate, $item, $DepartmentName) {
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

}
