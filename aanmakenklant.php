<?php
//connectie met database invoegen
include_once('include/connection.php');
session_start();

//////////////////////////////////////////
//gegevens uit form halen
$voornaam               = $_POST['vnaam'];
$familienaam            = $_POST['fnaam'];
$adres                  = $_POST['adres'];
$postcode               = $_POST['pcode'];
$stad                   = $_POST['gemeente'];
$telefoon               = $_POST['telefoon'];
$land                   = $_POST['land'];
$email                  = $_POST['email'];
$bedrijfsnaam           = $_POST['bedrijfsnaam'];
$btw                    = $_POST['btw'];
$gebruikersnaam         = $_POST['gebruikersnaam'];
$password               = $_POST['password'];
$website                = $_POST['website'];
$date                   = date("Y/m/d");



$STHklanten = $DBH->prepare('SELECT * FROM tblklanten');
$STHklanten->execute();
$STHklanten->setFetchMode(PDO::FETCH_OBJ);

while($row = $STHklanten->fetch()){

 $username = $row->klantgebruikersnaam;
 if ($gebruikersnaam == $username){
  $_SESSION['error'] = "De gebruikersnaam wordt al gebruikt door iemand anders. Gelieve een andere gebruikersnaam te kiezen aub.";;
  header('location:login.php');
  exit;
 }


 $emailadres = $row->klantmail;
 if($email == $emailadres){
  $_SESSION['error'] = "Dit e-mailadres wordt al gebruikt door iemand anders. Gelieve een ander e-mailadres in te geven aub.";
  header('location:login.php');
  exit;
 }
}

//gegevens invoegen in tblklanten
$STH = $DBH->prepare('INSERT INTO tblklanten
(klantvnaam,
klantfnaam,
klantstraat,
klantpcode,
klantgemeente,
klantland,
klanttelefoon,
klantbedrijf,
klantbtwnr,
klantgebruikersnaam,
klantpwd,
klantmail,
klantwebsite,
klantregistratiedatum)
 VALUES
(:klantvnaam,
:klantfnaam,
:klantstraat,
:klantpcode,
:klantgemeente,
:klantland,
:klanttelefoon,
:klantbedrijf,
:klantbtwnr,
:klantgebruikersnaam,
:klantpwd,
:klantmail,
:klantwebsite,
:klantdatum)');

$STH->bindParam(':klantvnaam', $voornaam);
$STH->bindParam(':klantfnaam', $familienaam);
$STH->bindParam(':klantstraat', $adres);
$STH->bindParam(':klantpcode', $postcode);
$STH->bindParam(':klantgemeente', $stad);
$STH->bindParam(':klantland', $land);
$STH->bindParam(':klanttelefoon', $telefoon);
$STH->bindParam(':klantbedrijf', $bedrijfsnaam);
$STH->bindParam(':klantbtwnr', $btw);
$STH->bindParam(':klantgebruikersnaam', $gebruikersnaam);
$STH->bindParam(':klantpwd', $password);
$STH->bindParam(':klantmail', $email);
$STH->bindParam(':klantwebsite', $website);
$STH->bindParam(':klantdatum', $date);


//query/vraag uitvoeren
$STH->execute();

$_SESSION['login'] = $gebruikersnaam;

///////////////////////////////////////////////////////////////////////////////
//laatst gebruikte klantenid ophalen
$lastid = $DBH->lastInsertId();

//gebruiker terugsturen naar vorige pagina
header('location:sendemail.php?klantid='.$lastid.'');
exit();

?>