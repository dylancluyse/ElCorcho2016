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
$regioid = $_GET['regioid'];

//query opbouwen
$STHdel = $DBH->prepare ('DELETE tblregio
FROM tblregio
WHERE regioid = :regioid');


//parameter opbouwen
$STHdel->bindParam(':regioid', $regioid);

//vraag uitvoeren
$STHdel->execute();

header('location:aanmakenregio.php');

exit();

?>