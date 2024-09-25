<?php


include "../initial.php";

$temp = new ReplaceHtml("../../template/APP_QC_ISSUE_REPORT/index.html");
echo $temp->getReplace();

sqlsrv_close($ConnSL);




//$dir = '../../template/APP_QC_ISSUE_REPORT/img_tag/';
//$files2 = scandir($dir, 1);
//echo '<pre>';
//print_r($files2);
//echo '</pre>';
//echo count($files2);
//
//function setConn($c) {
//    $this->StrConn = $c;
//}
//
//$CM = new CallModel();
//$CM->SyteLine_Models();
//$PO_QC = new po_qc_sl();
//$PO_QC->setConn($ConnSL);
//$query = " select * FROM STS_QC_ISSUE_img_upload where 1=1 ";
//$cSql = new SqlSrv();
//$rs0 = $cSql->SqlQuery($this->StrConn, $query);
//array_splice($rs0, count($rs0) - 1, 1);
//return $rs0;
//print_r($rs0);








//$myFile = "testFile.txt";
//$myFileLink = fopen($myFile, 'w') or die("can't open file");
//fclose($myFileLink);
//delete_files('sts_1911261120011.jpg');
//function delete_files($target) {
//    $target = '../../template/app_tag_photo_gallery/img_tag/'.$target;
//    if (is_dir($target)) {
//        $files = glob($target . '*', GLOB_MARK); //GLOB_MARK adds a slash to directories returned
//        foreach ($files as $file) {
//            delete_files($file);
//        }
//        rmdir($target);
//    } elseif (is_file($target)) {
//        unlink($target);
//    }
//    $files = glob($target . '*', GLOB_MARK); //GLOB_MARK adds a slash to directories returned
//    print_r($files);
//}

//
//$myFile = "testFile.txt";
//$myFileLink = fopen($myFile, 'w') or die("can't open file");
//fclose($myFileLink);
//$flagDelete = unlink($myFile) or die("Couldn't delete file");
//if ($flagDelete) {
//    echo 'delete';
//} else {
//    echo 'file not found';
//}