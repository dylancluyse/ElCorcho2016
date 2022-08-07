<?php
include_once('include/connection.php');

$klantid = $_GET['klantid'];

$STH = $DBH->prepare('SELECT * FROM tblklanten WHERE klantid = :klantid ');

$STH->bindParam(':klantid',$klantid);

//vraag uitvoeren
$STH->execute();

$aantal = $STH -> rowCount();

if($aantal==0){
} else {
    //methode van uitvoer
    $STH->setFetchMode(PDO::FETCH_OBJ);

    //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
    while($row = $STH->fetch()){
        $email = $row->klantmail;
        $vnaam = $row->klantvnaam;

        $STH->setFetchMode(PDO::FETCH_OBJ);

        $to = $email;
        $subject = 'Welkom bij El Corcho';

        $message = 'Beste ' . $vnaam . ',

Welkom bij El Corcho. U kan nu producten bestellen.

    Mvg,
El Corcho.';

        $headers = "From: info@elcorcho.be\r\n";
        $headers .= "X-Sender: <info@elcorcho.be>\r\n";
        $headers .= "X-Mailer: PHP \r\n";
        $headers .= "X-Priority: 1\r\n"; //1 is spoedbericht, 3 is normaal bericht;
        $headers .= "Return-Path: <info@elcorcho.be>\r\n";

//terugsturen naar formlogin.php
        if (mail($to, $subject, $message, $headers)) {
            header('location:index.php');
        } else {
            header('location:index.php?error=ml');
        }
        
    }
}


?>