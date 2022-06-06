<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}

$temp->setReplace("{content}", $temp->getTemplate("./template/mt_harbor_lists.html"));
$WriteList = "" ;
$i=0;
$keyword = "" ;
if(isset($_POST["txtKeyword"])){
$keyword = $_POST["txtKeyword"] ;
}
$List = new Harbor();
$List->StrConn = $conn ;
$txtStatus = "" ;
$Lists = $List->GetRowsWithCond(" HB_Name LIKE '%$keyword%'  ");
foreach($Lists as $index => $rows ){
  $i=$index+1;
  if($rows["HB_Status"] == 0 ){
    $txtStatus ="Berthed At";
  }else if($rows["HB_Status"] ==1 ){
    $txtStatus ="Ship To";
  }else if($rows["HB_Status"] ==2 ){
    $txtStatus ="Berthed At / Ship To";
  }
  $WriteList=$WriteList."<tr>
  <td class='td-num'>$i</td>
  <td class='td-text' >".$rows["HB_Name"]."</td>
  <td class='td-text' >$txtStatus</td>
  <td class='td-num' >
  <button class='btn btn-info' id='b-".$rows["IdRun"]."' type='button'><i class='fa fa-edit'></i></button>
   <a href='' class='btn btn-info' id='d-".$rows["IdRun"]."' ><i class='fa fa-trash'></i></a>
  </td>
  </tr>";
}

$temp->setReplace("{keyword}",$keyword);
$temp->setReplace("{lists}",$WriteList);
 ?>
