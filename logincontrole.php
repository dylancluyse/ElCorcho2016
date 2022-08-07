<?php
session_start();

//controleer of er op de knop geklikt is
if(!isset($_POST['btnlogin'])){
    header('location:login.php?error=1&lang=nl');
    exit();
}

//gegevens ophalen
$loginform = $_POST['login'];
$passwordform = $_POST['password'];

//connectie met de database
include_once('include/connection.php');

//vraag aan de database
$STH = $DBH->prepare('SELECT * FROM tblklanten WHERE klantgebruikersnaam = :username');

//parameter invullen
$STH->bindParam(':username', $loginform);

//uitvoeren
$STH->execute();

//tellen
$aantal = $STH->rowCount();

//aantal verschillend van 1 terugsturen
if($aantal != 1){
    $_SESSION['errorpwd'] = "Uw gebruikersnaam werd niet teruggevonden.";
    header('location:login.php?error=2&lang=nl');
    exit();
}

$STH->setFetchMode(PDO::FETCH_OBJ);

//password uit database lezen
while($row = $STH->fetch()){
    $passworddb = $row->klantpwd;
}

//controleer of paswoord uit DB = form
if($passwordform == $passworddb){
    //paswoorden zijn gelijk
    $_SESSION['login'] = $loginform;
    $_SESSION['password'] = $passwordform;

    //tijdduur bepalen hoelang een pagina ongeroerd mag openstaan
    $_SESSION['tijdsduur'] = time() + 60*10;

    header('location:index.php');

} else {
    //paswoorden zijn niet gelijk
    $_SESSION['errorpwd'] = "U heeft een fout wachtwoord ingegeven.";
    header('location:login.php?error=2&lang=nl');
    exit();
}