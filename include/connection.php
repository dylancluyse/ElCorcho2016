<?php
//verbinding met de database leggen

try{
    //gegevens in variabelen stoppen
    $host = 'localhost';
    $database = 'elcorcho';
    $username = 'elcorcho';
    $password = 'adminpass';

    //verbinding instellen
    $DBH = new PDO("mysql:host=$host;dbname=$database",$username,$password);

    //foutmodus instellen
    $DBH->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

} catch (PDOException $e){

    //foutmeldingen weergeven als er zijn
    echo $e->getMessage();
    
}