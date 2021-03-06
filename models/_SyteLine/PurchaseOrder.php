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

}
