<?php

include "../db.php";
include "PHPExcel.php";


$myexcel = new PHPExcel(); // Excel İmport Start
$myexcel->getActiveSheet()->setTitle("Ogrenciler"); // Excel Bottom Title name
$myexcel->getActiveSheet()->setCellValue("A1", "Urun Kodu"); // Excel Thead Title


// Connet Vt Table
$urunkod = $db->prepare("SELECT * FROM ogrenciler ");
$urunkod->execute(array());

$i = 2; // Start two Rows
while ($urunyaz = $urunkod->fetch(PDO::FETCH_ASSOC)) {
    $myexcel->getActiveSheet()->setCellValue("A" . $i, $urunyaz['isim']); // Exce Column Save

    $link = "https://tonyukukdemo.com/flora-liste/urun.php?urunid=".$urunyaz['id'];


    $myexcel->getActiveSheet()->getCellByColumnAndRow(0,$i)->getHyperlink()->setUrl($link);
    $i++;
}


// Save Excel 2007 file
#echo date('H:i:s') . " Write to Excel2007 format\n";
$downloadsexcel = PHPExcel_IOFactory::createWriter($myexcel, 'Excel2007');
ob_end_clean();
// We'll be outputting an excel file
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="exceladi.xlsx"');
$downloadsexcel->save('php://output');
exit;
