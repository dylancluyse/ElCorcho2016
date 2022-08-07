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
$producentid = $_GET['producentid'];

//query opbouwen
$STHdel = $DBH->prepare ('DELETE tblproducent
FROM tblproducent
WHERE producentid = :producentid');


//parameter opbouwen
$STHdel->bindParam(':producentid', $producentid);

//vraag uitvoeren
$STHdel->execute();

header('location:aanmakenproducent.php');

exit();

?>