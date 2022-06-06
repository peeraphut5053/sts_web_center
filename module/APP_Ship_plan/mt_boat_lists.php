<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}

$temp->setReplace("{content}", $temp->getTemplate("./template/mt_boat_lists.html"));
$WriteList = "" ;
$i=0;
$keyword = "" ;
if(isset($_POST["txtKeyword"])){
$keyword = $_POST["txtKeyword"] ;
}
$List = new Boat();
$List->StrConn = $conn ;

$Lists = $List->GetRowsWithCond(" Ship_MV LIKE '%$keyword%' OR Ship_LighterNo LIKE '%$keyword%'  ");
foreach($Lists as $index => $rows ){
  $i=$index+1;
  $WriteList=$WriteList."<tr>
  <td class='td-num'>$i</td>
  <td class='td-text' >".$rows["Ship_MV"]."</td>
  <td class='td-text' >".$rows["Ship_LighterNo"]."</td>
  <td class='td-num' >
  <button class='btn btn-info' id='b-".$rows["IdRun"]."' type='button'><i class='fa fa-edit'></i></button>
   <a href='' class='btn btn-info' id='d-".$rows["IdRun"]."' ><i class='fa fa-trash'></i></a>
  </td>
  </tr>";
}

$temp->setReplace("{keyword}",$keyword);
$temp->setReplace("{lists}",$WriteList);
 ?>
