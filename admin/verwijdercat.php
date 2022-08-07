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
$categorieid = $_GET['catid'];

//query opbouwen
$STHdel = $DBH->prepare ('DELETE tblcategorie
FROM tblcategorie
WHERE categorieid = :categorieid');


//parameter opbouwen
$STHdel->bindParam(':categorieid', $categorieid);

//vraag uitvoeren
$STHdel->execute();

header('location:aanmakencat.php');

exit();

?>