<?php

$mysqlsunucu = "localhost"; 
$mysqladi = "guven_sertifika";
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

    ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
