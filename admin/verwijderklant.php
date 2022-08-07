<?php
//connectie met database invoegen
include_once('../include/connection.php');
include_once('../include/accesscontrol.php');

// rij uit tblklanten verwijderen
$klantid = $_GET['klantid'];
$STH = $DBH->prepare('DELETE FROM tblklanten WHERE klantid = :klantid');
$STH->bindParam(':klantid', $klantid);
$STH->execute();


// alle producten per bestelling verwijderen --> bestellingid meegeven
$STH = $DBH->prepare('SELECT bestellingid FROM tblbestellingen WHERE klantid = :klantid');
$STH->bindParam(':klantid', $_GET['klantid']);
$STH->execute();

while($row = $STH->fetch()){
    $bestellingid = $row->bestellingid;

    //verwijder alle producten die gekoppeld zijn aan een bestellling van een klant
    $STH = $DBH->prepare('DELETE FROM tblprodperbestelling WHERE bestellingid = :bestellingid');
    $STH->bindParam(':bestellingid', $bestellingid);
    $STH->execute();
}


//verwijder alle bestellingen die gekoppeld zijn aan de klant
$STH = $DBH->prepare('DELETE FROM tblbestellingen WHERE klantid = :klantid');
$STH->bindParam(':klantid', $_GET['klantid']);
$STH->execute();

header('location:klantlijst.php');
?>
