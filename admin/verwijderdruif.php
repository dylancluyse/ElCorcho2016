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
$druifid = $_GET['druifid'];

//query opbouwen
$STHdel = $DBH->prepare ('DELETE tbldruiven
FROM tbldruiven
WHERE druifid = :druifid');


//parameter opbouwen
$STHdel->bindParam(':druifid', $druifid);

//vraag uitvoeren
$STHdel->execute();

header('location:aanmakendruif.php');

exit();

?>