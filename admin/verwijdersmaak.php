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
$smaakid = $_GET['smaakid'];

//query opbouwen
$STHdel = $DBH->prepare ('DELETE tblsmaakprofiel
FROM tblsmaakprofiel
WHERE smaakid = :smaakid');


//parameter opbouwen
$STHdel->bindParam(':smaakid', $smaakid);

//vraag uitvoeren
$STHdel->execute();

header('location:aanmakensmaak.php');

exit();

?>