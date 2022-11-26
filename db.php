<?php

$mysqlsunucu = "localhost";
//$mysqladi = "flora";
$mysqladi = "excel_vt";
$mysqlkullanici = "root";
$mysqlsifre = "";

try {
    $db = new PDO("mysql:host=$mysqlsunucu;dbname=$mysqladi;charset=utf8", $mysqlkullanici, $mysqlsifre);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

catch(PDOException $e)
    {
    echo "Bağlantı hatası: " . $e->getMessage();
    }


?>

