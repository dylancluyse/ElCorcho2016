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
$blogid = $_GET['blogID'];

//query opbouwen
$STHdel = $DBH->prepare ('DELETE
FROM tblblog
WHERE blogid = :blogid');


//parameter opbouwen
$STHdel->bindParam(':blogid', $blogid);

//vraag uitvoeren
$STHdel->execute();

header('location:aanmakenblog.php');

exit();

?>