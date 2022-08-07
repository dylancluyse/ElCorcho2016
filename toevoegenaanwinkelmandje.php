<?php
include_once('include/connection.php');

if(isset($_POST['submit'])) {

    session_start();
    $sessionid = session_id();

    $productaantal = $_POST['aantal'];
    $productid = $_GET['productid'];
    $datum = date("Y-m-d H:i:s");

    $STHwm = $DBH->prepare('SELECT * FROM tbltijdelijkewinkelwagen WHERE sessionid = :sessionid AND productid = :productid');
    $STHwm->bindParam(':productid',$productid);
    $STHwm->bindParam(':sessionid',$sessionid);
    $STHwm->execute();

    $STHwm->setFetchMode(PDO::FETCH_OBJ);
    while ($row = $STHwm->fetch()) {
        $oudaantal = $row->aantal;
        $nieuwaantal = $oudaantal + $productaantal;
    }

    $aantal = $STHwm->rowCount();

    if ($aantal > 0){

        $STH = $DBH->prepare('UPDATE tbltijdelijkewinkelwagen SET aantal = :aantal WHERE sessionid = :sessionid AND productid = :productid ;');

        $STH->bindParam(':productid',$productid);
        $STH->bindParam(':sessionid',$sessionid);
        $STH->bindParam(':aantal',$nieuwaantal);
        $STH->execute();
        header('location:prod.php');
        exit;

    } else {

        $STH = $DBH->prepare('SELECT * FROM tblproducten WHERE productid = :productid');
        $STH->bindParam(':productid',$productid);
        $STH->execute();
        $STH->setFetchMode(PDO::FETCH_OBJ);

        while ($row = $STH->fetch()) {
            $prijs = $row->prijs;
            $promotie = $row->promotie;

            if ($promotie > 0){
                $prijs = $promotie;
            } else {
                $prijs = $prijs;
            }
        }

        $STH = $DBH->prepare('INSERT INTO tbltijdelijkewinkelwagen (sessionid, productid, aantal, prijs, datum)VALUES(:sessionid,:productid,:aantal,:prijs, :datum)');

        $STH->bindParam(':sessionid', $sessionid);
        $STH->bindParam(':productid', $productid);
        $STH->bindParam(':aantal', $productaantal);
        $STH->bindParam(':prijs', $prijs);
        $STH->bindParam(':datum', $datum);
        $STH->execute();

        echo $productid . '<br>';
        echo $aantal . '<br>';
        echo $datum . '<br>';
        echo $sessionid . '<br>';
        header('location:prod.php');
        exit();
    }
} else {
    header('location:prod.php');
}
?>