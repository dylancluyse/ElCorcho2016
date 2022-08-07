<?php
include_once('include/connection.php');
session_start();

$STH = $DBH->prepare('DELETE FROM tblklanten WHERE klantgebruikersnaam = :klantlogin');
$klantlogin = $_SESSION['login'];
$STH->bindParam(':klantlogin', $klantlogin);
$STH->execute();




$STHbes1 = $DBH->prepare('SELECT bestellingid FROM tblbestellingen WHERE klantid = :klantid');
$STHbes1->bindParam(':klantid', $_GET['klantid']);
$STHbes1->execute();




while($row = $STHbes1->fetch()){
    $bestellingid = $row->bestellingid;

    //verwijder alle producten die gekoppeld zijn aan een bestellling van een klant
    $STHprod = $DBH->prepare('DELETE FROM tblprodperbestelling WHERE bestellingid = :bestellingid');
    $STHprod->bindParam(':bestellingid', $bestellingid);
    $STHprod->execute();
}




//verwijder alle bestellingen die gekoppeld zijn aan de klant
$STHbes2 = $DBH->prepare('DELETE FROM tblbestellingen WHERE klantid = :klantid');
$STHbes2->bindParam(':klantid', $_GET['klantid']);
$STHbes2->execute();


unset($_SESSION['login']);

header('location:index.php');
?>