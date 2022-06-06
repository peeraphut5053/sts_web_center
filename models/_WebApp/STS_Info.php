<?php

class STS_Info {

    var $StrConn = "";
    public $_site_ref = "";
    public $id = "";
    public $name = "";
    public $fullname = "";
    public $addr1 = "";
    public $addr2 = "";
    public $addr3 = "";
    public $tel1 = "";
    public $tel2 = "";
    public $fax1 = "";
    public $fax2 = "";
    public $email = "";
    public $status = "";
    function setConn($c) {
        $this->StrConn = $c;
    }

    function GetProperties() {
        $query = "SELECT * FROM STS_Info ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        $this->id = $rs0[1]["id"];
        $this->name = $rs0[1]["name"];
        $this->fullname = $rs0[1]["fullname"];
        $this->addr1 = $rs0[1]["addr1"];
        $this->addr2 = $rs0[1]["addr2"];
        $this->addr3 = $rs0[1]["addr3"];
        $this->tel1 = $rs0[1]["tel1"];
        $this->tel2 = $rs0[1]["tel2"];
        $this->fax1 = $rs0[1]["fax1"];
        $this->fax2 = $rs0[1]["fax2"];
        $this->status = $rs0[1]["status"];
        $this->email = $rs0[1]["email"];
    }

}
