<?php
while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "./initial.php";
$TagRows = new Item();
$TagRows->setConn($conn_tag);
$tableContent="";$x = 0 ;

if($txtSearch){
     $Items = $TagRows->GetRowsWithCond(" id LIKE '%$txtSearch%' ");
    
}
   else{
      
        $Items = $TagRows->GetRowsWithCond(" 1 = 1 ");
   }

foreach($Items as $id => $rows ){
    $x = $id +1 ;
    $tableContent = $tableContent."<tr>"
            . "<td><a href='#' OnClick='Select(this.id);' class='btn btn-success' id='".$rows["id"]."' data-tag='".$rows["id"]."' ><i class='fa fa-check'></i></a></td>"
            . "<td>$x</td>"
            . "<td>".$rows["id"]."</td><td>".$rows["item"]."</td>"
            . "<td>".$rows["qty1"]."</td></tr>";
}

echo $tableContent ;

sqlsrv_close($conn);