<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
${$key} = trim($data);
}
require_once "../initial.php";

$CallModel = new CallModel();
$CallModel->SyteLine_Models();

$data = new ChartGraph();
$data->setConn($ConnSL);

$data = $data->GetData();
echo json_encode($data);





//
//$data = array(
//array(
//"y" => '2019-01',
// "item1" => 1000000
//),
// array(
//"y" => '2019-02',
// "item1" => 2000000
//),
// array(
//"y" => '2019-03',
// "item1" => 3000000
//),
// array(
//"y" => '2019-04',
// "item1" => 4000000
//),
// array(
//"y" => '2019-05',
// "item1" => 5000000
//),
// array(
//"y" => '2019-06',
// "item1" => 6000000
//)
//);



