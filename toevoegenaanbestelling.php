<?php
//connectie met database invoegen
include_once('include/connection.php');
session_start();
?>

<?php
// # producten in het winkelwagentje = 0 --> terugsturen
$STH = $DBH->prepare('SELECT * FROM tbltijdelijkewinkelwagen WHERE sessionid = :sessionid');
$STH->bindParam(':sessionid',session_id());
$STH->execute();
$producteninwinkelwagen = $STH->rowCount();

if($producteninwinkelwagen == 0){
    header('location:winkelwagentje.php');
}





//gegevens toevoegen in tblbestellingen//
$leveringsid = $_POST['leveringswijze'];
$betaalid = $_POST['betaalwijze'];

$postcode = $_POST['postcode'];
$adres = $_POST['adres'];
$gemeente = $_POST['gemeente'];
$leveringsadres =  $postcode . ' ' . $gemeente. ', ' . $adres;
$datumtijd = date("Y-m-d H:i:s");
$statusid = "1";

$STH = $DBH->prepare('SELECT klantid, klantmail, klantvnaam FROM tblklanten WHERE klantgebruikersnaam = :klantlogin');
$STH->bindParam(':klantlogin', $_SESSION['login']);
$STH->execute();
$STH->setFetchMode(PDO::FETCH_OBJ);

while($row = $STH->fetch()) {
    $klantid = $row->klantid;
    $klantmail = $row->klantmail;
    $klantvnaam = $row->klantvnaam;
}





//bestellinggegevens ingeven in tblbestellingen
$STH = $DBH->prepare('INSERT INTO tblbestellingen (klantid,leveringsid,betaalid,datumtijd, statusid, adres)VALUES (:klantid,:leveringsid,:betaalid,:datumtijd, :statusid, :adres)');
$STH->bindParam(':klantid', $klantid);
$STH->bindParam(':leveringsid', $leveringsid);
$STH->bindParam(':betaalid', $betaalid);
$STH->bindParam(':datumtijd', $datumtijd);
$STH->bindParam(':statusid', $statusid);
$STH->bindParam(':adres', $leveringsadres);
$STH->execute();

//bestellingid opslaan onder variabele $bestellingid
$bestellingid = $DBH->lastInsertId();


$STH = $DBH->prepare('SELECT * FROM tblleveringsmogelijkheden WHERE leveringsid = :leveringsid');
$STH->bindParam(':leveringsid',$leveringsid);
$STH->execute();
$STH->setFetchMode(PDO::FETCH_OBJ);
while ($row = $STH->fetch()){
    $leveringsnaam = $row->leveringswnaam;
    $leveringsprijs = $row->leveringsprijs;
}


$STH = $DBH->prepare('SELECT * FROM tblbetaalwijze WHERE betaalid = :betaalid');
$STH->bindParam(':betaalid',$betaalid);
$STH->execute();
$STH->setFetchMode(PDO::FETCH_OBJ);
while ($row = $STH->fetch()){
    $betaalnaam = $row->betaalwnaam;
}




//mail sturen naar klant met info rond bestelling
$STH = $DBH->prepare('SELECT * FROM tbltijdelijkewinkelwagen INNER JOIN tblproducten ON tbltijdelijkewinkelwagen.productid = tblproducten.productid WHERE sessionid = :sessionid');
$STH->bindParam(':sessionid',session_id());
$STH->execute();
$STH->setFetchMode(PDO::FETCH_OBJ);
$aantalproducten = $STH->rowCount();

$productnaam = '';
$totaalprijs = 0;

while($row = $STH->fetch()) {
    $productnaamdb = $row->prodnaam;
    $prijs = $row->prijs;
    $aantal = $row->aantal;

    $totaalprijs = $totaalprijs + (($prijs*$aantal)*1.21) + $leveringsprijs;

    $productnaam = $productnaamdb .' / '. $productnaam;
}

$totaalprijs = '€ '. round($totaalprijs,2);


$to = $klantmail;
$subject = 'Bestelling bij El Corcho';
$message = 'Beste ' . $klantvnaam . ',

U heeft in totaal '. $aantalproducten .' product(en) besteld. Volgende producten werden besteld:
'. $productnaam . '

Uw bestellingID is '.$bestellingid.'.

Het totaal van de bestelling is '. $totaalprijs. '.

De levering zal gebeuren via '.$leveringsnaam.'

U heeft gekozen om te betalen via '.$betaalnaam.'

U kan uw bestelling terugvinden op onze site bij het tabblad "Account". Let op: u moet hiervoor ingelogd zijn.

    Mvg,
El Corcho.';

$headers = "From: info@elcorcho.be\r\n";
$headers .= "X-Sender: <info@elcorcho.be>\r\n";
$headers .= "X-Mailer: PHP \r\n";
$headers .= "X-Priority: 1\r\n"; //1 is spoedbericht, 3 is normaal bericht;
$headers .= "Return-Path: <info@elcorcho.be>\r\n";



//terugsturen naar formlogin.php
if (mail($to, $subject, $message, $headers)) {
    header('location:index.php');
} else {
    header('location:index.php?error=ml');
}


$sessionid = session_id();
$STH = $DBH->prepare('SELECT * FROM tbltijdelijkewinkelwagen WHERE sessionid = :sessionid');
$STH->bindParam(':sessionid', $sessionid);
$STH->execute();
$STH->setFetchMode(PDO::FETCH_OBJ);
$aantal = $STH->rowCount();

if($aantal != "0") {
    while ($row = $STH->fetch()) {
        $productid = $row->productid;
        $aantal = $row->aantal;
        $prijs = $row->prijs;

        // alle producten van tbltijdelijkwinkelwagentje in tblprodperbestelling steken die horen bij sessionid + login
        $STHbes = $DBH->prepare('INSERT INTO tblprodperbestelling (productid, bestellingid, prijs, aantal) VALUE (:productid, :bestellingid, :prijs, :aantal)');
        $STHbes->bindParam(':productid', $productid);
        $STHbes->bindParam(':bestellingid', $bestellingid);
        $STHbes->bindParam(':prijs', $prijs);
        $STHbes->bindParam(':aantal', $aantal);
        $STHbes->execute();

        // # producten verwijderen uit voorraad
        $STHins = $DBH->prepare('SELECT voorraad FROM tblproducten WHERE productid = :productid');
        $STHins->bindParam(':productid', $productid);
        $STHins->execute();
        $STHins->setFetchMode(PDO::FETCH_OBJ);
        while($row = $STHins->fetch()){
            $oudevoorraad = $row->voorraad;
        }

        $STHupd = $DBH->prepare('UPDATE tblproducten SET voorraad = :nieuwevoorraad WHERE productid = :productid');
        $nieuwevoorraad = $oudevoorraad - $aantal;

        $STHupd->bindParam(':nieuwevoorraad', $nieuwevoorraad);
        $STHupd->bindParam(':productid', $productid);
        $STHupd->execute();
    }

    $STHverwijderen = $DBH->prepare('DELETE FROM tbltijdelijkewinkelwagen WHERE sessionid = :sessionid');
    $STHverwijderen->bindParam(':sessionid', session_id());
    $STHverwijderen->execute();

    header('location:showcheckout.php?bestellingID='. $bestellingid);
    exit();

} else {
    header('location:winkelwagentje.php');
    exit();
}

?>