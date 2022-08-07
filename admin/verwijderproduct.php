<head>
    <title>Admin | El Corcho</title>
</head>
<?php
//connectie met database invoegen
include_once('../include/connection.php');
include_once('../include/accesscontrol.php');
?>

<?php
//ID op halen via URL
$productid = $_GET['productid'];

$STH = $DBH->prepare ('DELETE tblproducten FROM tblproducten WHERE productid = :productid');
$STH->bindParam(':productid', $productid);
$STH->execute();

$STH = $DBH->prepare('DELETE tblprodfoto FROM tblprodfoto WHERE productid = :productid');
$STH->bindParam(':productid', $productid);
$STH->execute();

header('location:prodlijst.php');

exit();

?>