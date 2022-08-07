<?php

session_start();

include_once('../include/connection.php');

if(isset($_POST['btnlogin'])){

    $login = $_POST['login'];
    $password = $_POST['password'];

    //via named placeholders
    $STH = $DBH->prepare('SELECT * FROM tbladmin WHERE adminlogin = :login');

    //vraag uitvoeren
    $STH->execute();

    //named placeholders aanmaken
    $STH->bindParam(':login', $login);

    //query/vraag uitvoeren
    $STH->execute();

    $aantal = $STH -> rowCount();

    if($aantal==0){
        header('location:login.php?error=2');
    } else {
        //methode van uitvoer
        $STH->setFetchMode(PDO::FETCH_OBJ);

        //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
        while($row = $STH->fetch()){

            $passworddb           = $row->adminpwd;

            if ($password == $passworddb) {

                $_SESSION['password'] = $password;

                //tijdduur bepalen hoelang een pagina ongeroerd mag openstaan
                $_SESSION['tijdsduur'] = time() + 60*10;

                //doorsturen volgens niveau
                header('location:index.php');

                exit();

            } else {
                header('location:login.php?error=3');
            }
        }
    }
} else {
    header('location:login.php?error=1');
}

?>