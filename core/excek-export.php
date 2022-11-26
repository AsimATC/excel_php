<?php

include "../db.php";
include "PHPExcel.php";


$myexcel = new PHPExcel(); // Excel İmport Start
$myexcel->getActiveSheet()->setTitle("Öğrenciler"); // Excel Bottom Title name
$myexcel->getActiveSheet()->setCellValue("A1", "Ürün Kodu"); // Excel Thead Title

// Connet Vt Table
$urunkod = $db->prepare("SELECT * FROM ogrenciler ");

$i = 2; // Start two Rows
while($urunyaz = $urunkod->fetch(PDO::FETCH_ASSOC)) {
    $myexcel->getActiveSheet()->setCellValue("A" . $i, $urunyaz['isim']); // Exce Column Save
    $i++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="urunler.xlsx"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');

header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); 
header ('Cache-Control: cache, must-revalidate'); 
header ('Pragma: public'); 

$download = PHPExcel_IOFactory::createWriter($myexcel, 'Excel2007');
$download->save('php://output');
exit;
?>