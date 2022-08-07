<?php
session_start();

//controleer of de sessie bestaat
if(!isset($_SESSION['password'])){
    header('location:login.php?error=1');
    exit();
}

//controleer of de tijdsduur niet verstreken is
if(time()>$_SESSION['tijdsduur']){
    //tijdsduur is verstreken
    header('location:login.php?error=5');
    exit();
} else {
    //tijdsduur is nog niet verstreken -> extra tijd toevoegen
    $_SESSION['tijdsduur'] = time() + 60*40;
}

//gegevens ophalen
$passworduser = $_SESSION['password'];

//connectie met de database
include_once('connection.php');

//vraag aan de database
$STH = $DBH->prepare('SELECT * FROM tbladmin WHERE adminpwd = :adminpwd');

//parameter invullen
$STH->bindParam(':adminpwd', $passworduser);

//uitvoeren
$STH->execute();

//tellen
$aantal = $STH->rowCount();

//aantal verschillend van 1 terugsturen
if($aantal != 1){
    header('location:login.php?error=2');
    exit();
}

$STH->setFetchMode(PDO::FETCH_OBJ);

//password uit database lezen
while($row = $STH->fetch()){
    $passworddb = $row->adminpwd;
}

//controleer of paswoord uit DB = form
if(password_verify($passworduser,$passworddb)){
    //paswoorden zijn niet gelijk
    header('location:login.php?error=3');
    exit();
}