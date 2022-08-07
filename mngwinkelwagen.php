<?php
include_once('include/connection.php');
session_start();

if (!isset($_GET['action'])){
    header('location:winkelwagentje.php');
}
    $action = $_GET['action'];

switch($action){
    case "del":
        $productid = $_GET['product'];
        $sessionid = session_id();

        $STH= $DBH->prepare('DELETE FROM tbltijdelijkewinkelwagen WHERE sessionid = :sessionid AND productid = :productid');
        $STH->bindParam(':sessionid', $sessionid);
        $STH->bindParam(':productid', $productid);
        $STH->execute();

        header('location:winkelwagentje.php');
        break;

    case "upd":
        $productid = $_GET['product'];
        $sessionid = session_id();
        $aantal = $_GET['aantal'];
        $wijzig = $_GET['wijziging'];

        if($wijzig == "up") {
            $nieuwaantal = $aantal + 1;
        }
        if($wijzig == "down"){
            if($aantal == 0){
                $nieuwaantal = 0;
            } else {
                $nieuwaantal = $aantal - 1;
            }
        }

        $STH = $DBH->prepare('UPDATE tbltijdelijkewinkelwagen SET aantal = :aantal WHERE sessionid = :sessionid AND productid = :productid ;');

        $STH->bindParam(':productid',$productid);
        $STH->bindParam(':sessionid',$sessionid);
        $STH->bindParam(':aantal',$nieuwaantal);
        $STH->execute();

        header('location:winkelwagentje.php');
        break;

    case "del_all":
        $sessionid = session_id();

        $STH= $DBH->prepare('DELETE FROM tbltijdelijkewinkelwagen WHERE sessionid = :sessionid');
        $STH->bindParam(':sessionid', $sessionid);
        $STH->execute();

        header('location:winkelwagentje.php');
        break;

}
?>