<?php

class Harbor {
  Public $StrConn = "";
  public $_IdRun = 0 ;
  public $_HB_Name = "";
  public $_HB_Status = "";
  public $IdRun =0;
  public $HB_Name= "";
  public $HB_Status = "";
  private $table = "STS_MT_Harbor" ;
  function setConn($c) {
      $this->StrConn = $c;
  }
  function GetProperty(){
    $cSql = new SqlSrv();
    $sql0 = "SELECT IdRun , HB_Name ,HB_Status   FROM ".$this->table."  WHERE IdRun = ".$this->_IdRun."  ";
    $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
    $this->IdRun = $rs0[1]["IdRun"] ;
    $this->HB_Name = $rs0[1]["HB_Name"] ;
    $this->HB_Status = $rs0[1]["HB_Status"] ;
  }

  function GetRowsWithCond($sWhere){
    $cSql = new SqlSrv();
    $sql0 = "SELECT  IdRun , HB_Name ,HB_Status  FROM ".$this->table."  WHERE $sWhere ";
    $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
    array_splice($rs0, count($rs0) - 1, 1);
    return $rs0;
  }
  function isDup(){
    $cSql = new SqlSrv();
    $sql0 = "SELECT count(IdRun) as CountRecs  FROM ".$this->table."  WHERE HB_Name = '".$this->_HB_Name."' ";
    $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
    if($rs0[1]["CountRecs"] >=1 ){
        return 1 ;
    }else{
      return 0 ;
    }

  }
  function isDupWhenEdit($Old){
    $cSql = new SqlSrv();
    $sql0 = "SELECT count(IdRun) as CountRecs  FROM ".$this->table."  WHERE HB_Name !='$Old' AND HB_Name= '".$this->HB_Name."' ";
    $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
    if($rs0[1]["CountRecs"] >=1 ){
        return 1 ;
    }else{
      return 0 ;
    }
  }
  function Insert(){
    $cSql = new SqlSrv();
    $sql0 = "INSERT INTO ".$this->table." (HB_Name,HB_Status) VALUES ('".$this->_HB_Name."',".$this->_HB_Status.")  ";
    $rs0 = $cSql->IsUpDel($this->StrConn, $sql0);
    return $rs0;

  }
  function Update(){
    $cSql = new SqlSrv();
    $sql0 = "UPDATE ".$this->table." SET HB_Name='".$this->_HB_Name."' ,HB_Status =".$this->_HB_Status." WHERE IdRun =".$this->_IdRun;
    $rs0 = $cSql->IsUpDel($this->StrConn, $sql0);
    return $rs0;

  }
  function Delete(){
    $cSql = new SqlSrv();
    $sql0 = "DELETE FROM ".$this->table." WHERE IdRun =   ".$this->_IdRun ;
    $rs0 = $cSql->IsUpDel($this->StrConn, $sql0);
    return $rs0;
  }
  function Populate($sWhere , $DisPlay,$SelectedId){
    //0 = display Name
    //1 = Display Status

    //SelectedId  = IdRun
    $cSql = new SqlSrv();
    $ToDisplay = "" ;
    $ToSelected = "" ;
    $itemStr = "" ;
    $sql0 = "SELECT IdRun , HB_Name,HB_Status  FROM ".$this->table."  WHERE $sWhere ";
    $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
    array_splice($rs0, count($rs0) - 1, 1);
    if($DisPlay==0){
      $ToDisplay = "HB_Name"  ;
    }else if ($DisPlay ==1){
      $ToDisplay="HB_Status";
    }else{
      return "N/A" ;
    }
    $itemStr = $itemStr."<option  value='0'>..Select..</option>";
    foreach($rs0 as $index => $rows){
      if($SelectedId == $rows["IdRun"]){
        $ToSelected = "selected" ;
      }else{
        $ToSelected ="";
      }
        $itemStr = $itemStr . "<option $ToSelected value='".$rows["IdRun"]."'>".$rows[$ToDisplay]."</option>" ;
      // $itemStr = $itemStr . "  $ToSelected value='".$rows["IdRun"]." ".$rows[$ToDisplay]." " ;
    }
    return $itemStr;
  }
  function PopulateAll($DisPlay,$SelectedId){
    //0 = display Name
    //1 = Display Status

    //SelectedId  = IdRun
    $cSql = new SqlSrv();
    $ToDisplay = "" ;
    $ToSelected = "" ;
    $itemStr = "" ;
    $sql0 = "SELECT IdRun , HB_Name,HB_Status  FROM ".$this->table."  ";
    $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
    array_splice($rs0, count($rs0) - 1, 1);
    if($DisPlay==0){
      $ToDisplay = "HB_Name"  ;
    }else if ($DisPlay ==1){
      $ToDisplay="HB_Status";
    }else{
      return "N/A" ;
    }
    $itemStr = $itemStr."<option  value='0'>..Select..</option>";
    foreach($rs0 as $index => $rows){
      if($SelectedId == $rows["IdRun"]){
        $ToSelected = "selected" ;
      }else{
        $ToSelected ="";
      }
        $itemStr = $itemStr . "<option $ToSelected value='".$rows["IdRun"]."'>".$rows[$ToDisplay]."</option>" ;
      // $itemStr = $itemStr . "  $ToSelected value='".$rows["IdRun"]." ".$rows[$ToDisplay]." " ;
    }
    return $itemStr;
  }
}
