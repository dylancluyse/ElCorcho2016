<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>El Corcho | Admin</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/datepicker3.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <!--Icons-->
    <script src="js/lumino.glyphs.js"></script>
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
    <?php
    //connectie met database invoegen
    include_once('../include/connection.php');
    include_once('../include/accesscontrol.php');
    ?>
</head>


<?php
//op knop geklikt?
if(isset($_POST['submit'])){

    $klantid                = $_GET['klantid'];
    $voornaam               = $_POST['vnaam'];
    $familienaam            = $_POST['fnaam'];
    $adres                  = $_POST['adres'];
    $postcode               = $_POST['pcode'];
    $stad                   = $_POST['gemeente'];
    $telefoon               = $_POST['telefoon'];
    $land                   = $_POST['land'];
    $email                  = $_POST['email'];
    $bedrijfsnaam           = $_POST['bedrijfsnaam'];
    $btw                    = $_POST['bedrijfbtw'];
    $gebruikersnaam         = $_POST['gebruikersnaam'];
    $password               = $_POST['password'];
    $website                = $_POST['website'];

    $STH = $DBH->prepare('UPDATE tblklanten SET klantvnaam = :klantvnaam, klantfnaam = :klantfnaam, klantstraat = :klantstraat, klantpcode = :klantpcode
, klantgemeente = :klantgemeente, klantland = :klantland, klanttelefoon = :klanttelefoon, klantbedrijf = :klantbedrijf, klantbtwnr = :klantbtwnr,
klantgebruikersnaam = :klantgebruikersnaam, klantpwd = :klantpwd, klantmail = :klantmail, klantwebsite = :klantwebsite WHERE klantid = :klantid');

    $STH->bindParam(':klantid', $klantid);
    $STH->bindParam(':klantvnaam', $voornaam);
    $STH->bindParam(':klantfnaam', $familienaam);
    $STH->bindParam(':klantstraat', $adres);
    $STH->bindParam(':klantpcode', $postcode);
    $STH->bindParam(':klantgemeente', $stad);
    $STH->bindParam(':klantland', $land);
    $STH->bindParam(':klanttelefoon', $telefoon);
    $STH->bindParam(':klantbedrijf', $bedrijfsnaam);
    $STH->bindParam(':klantbtwnr', $btw);
    $STH->bindParam(':klantgebruikersnaam', $gebruikersnaam);
    $STH->bindParam(':klantpwd', $password);
    $STH->bindParam(':klantmail', $email);
    $STH->bindParam(':klantwebsite', $website);

    //query/vraag uitvoeren
    $STH->execute();

    //gebruiker terugsturen naar vorige pagina
    header('location:klantlijst.php');
    exit();
}
?>

<body>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php"><span>El Corcho</span>Admin</a>
        </div>
    </div><!-- /.container-fluid -->
</nav>

<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
    <ul class="nav menu">
        <li class="parent active">
            <a href="prodlijst.php"><span data-toggle="collapse" href="#sub-item-1"><svg class="glyph stroked chevron-down"><use xlink:href="#stroked-chevron-down"></use></svg></span>Producten</a>
            <ul class="children collapse" id="sub-item-1">
                <li>
                    <a class="" href="aanmakenprod.php">
                        <svg class="glyph stroked chevron-right"><use xlink:href="#stroked-pencil"></use></svg> Nieuw product
                    </a>
                </li>
                <li>
                    <a class="" href="aanmakenregio.php">
                        <svg class="glyph stroked chevron-right"><use xlink:href="#stroked-pencil"></use></svg> Regio's</a>
                </li>
                <li>
                    <a class="" href="aanmakencat.php">
                        <svg class="glyph stroked chevron-right"><use xlink:href="#stroked-pencil"></use></svg> Categorieën
                    </a>
                </li>
                <li>
                    <a class="" href="aanmakensmaak.php">
                        <svg class="glyph stroked chevron-right"><use xlink:href="#stroked-pencil"></use></svg> Smaakprofielen
                    </a>
                </li>
                <li>
                    <a class="" href="aanmakenblog.php">
                        <svg class="glyph stroked chevron-right"><use xlink:href="#stroked-pencil"></use></svg> Blog
                    </a>
                </li>
                <li>
                    <a class="" href="aanmakenproducent.php">
                        <svg class="glyph stroked chevron-right"><use xlink:href="#stroked-pencil"></use></svg> Producent
                    </a>
                </li>
                <li>
                    <a class="" href="aanmakendruif.php">
                        <svg class="glyph stroked chevron-right"><use xlink:href="#stroked-pencil"></use></svg> Druivenrassen
                    </a>
                </li>
            </ul>
        </li>
        <li><a href="stock.php"><svg class="glyph stroked pencil"><use xlink:href="#stroked-app-window"></use></svg> Stock</a></li>
        <li><a href="bestellingen.php"><svg class="glyph stroked app-window"><use xlink:href="#stroked-app-window"></use></svg> Bestellingen</a></li>
        <li class="active"><a href="klantlijst.php"><svg class="glyph stroked app-window"><use xlink:href="#stroked-app-window"></use></svg> Klanten</a></li>
        <li><a href="wijzigpwd.php"><svg class="glyph stroked app-window"><use xlink:href="#stroked-app-window"></use></svg> Wachtwoord veranderen</a></li>
    </ul>
</div><!--/.sidebar-->

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="index.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
            <li class="active">klanten</li>
            <li class="active">wijzigen</li>
        </ol>
    </div><!--/.row-->

    <?php

    $klantid = $_GET['klantid'];

    $STH = $DBH->prepare('SELECT * FROM tblklanten WHERE klantid = :klantid');

    $STH->bindParam(':klantid', $klantid);

    //vraag uitvoeren
    $STH->execute();

    $aantal = $STH -> rowCount();

    if($aantal==0){
    } else {
        //methode van uitvoer
        $STH->setFetchMode(PDO::FETCH_OBJ);

        //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
        while($row = $STH->fetch()){
            $klantvnaam   = $row->klantvnaam;
            $klantfnaam    = $row->klantfnaam;
            $klantadres = $row->klantstraat;
            $klantgemeente = $row->klantgemeente;
            $klantpcode = $row->klantpcode;
            $klanttelefoon = $row->klanttelefoon;
            $klantbedrijf = $row->klantbedrijf;
            $klantbtwnr = $row->klantbtwnr;
            $klantgebruikersnaam = $row->klantgebruikersnaam;
            $klantpwd = $row->klantpwd;
            $klantmail = $row->klantmail;
            $klantwebsite = $row->klantwebsite;
            $klantland = $row->klantland;
            $klantregistratiedatum = $row->klantregistratiedatum;
        }
    }
    ?>

    <!--form voor het aanmaken van nieuwe smaken-->
    <form class="form-horizontal" id="form_members" role="form" action="wijzigklant.php?klantid=<?php echo $klantid?>" method="POST">

        <legend>naam</legend>
        <div class="form-group">
        <label for="firstname" class="col-sm-2">voornaam & familienaam</label>
        <div class="col-sm-2 required">
            <input type="text" class="form-control" name="vnaam" id="klantfnaam" placeholder="" required autofocus maxlength="100" value="<?php echo $klantvnaam; ?>">
        </div>

        <div class="col-sm-2 required">
            <input type="text" class="form-control" name="fnaam" id="klantfnaam" placeholder="" required autofocus maxlength="100" value="<?php echo $klantfnaam; ?>">
        </div>
        </div>



        <legend>Adres</legend>
        <div class="form-group">
        <label for="firstname" class="col-sm-2">Straat + Huisnr</label>
            <div class="col-sm-4 required">
                <input type="text" class="form-control" name="adres" id="klantstraat" placeholder="" autofocus maxlength="100" value="<?php echo $klantadres; ?>">
            </div>
        </div>

        <div class="form-group">
        <label for="regio" class="col-sm-2">Postcode + Gemeente</label>
            <div class="col-sm-1 required">
                <input type="text" class="form-control" name="pcode" id="klantpcode" placeholder="" required autofocus maxlength="100" value="<?php echo $klantpcode; ?>">
            </div>
            <div class="col-sm-3 required">
                <input type="text" class="form-control" name="gemeente" id="klantgemeente" placeholder="" autofocus maxlength="100" value="<?php echo $klantgemeente; ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="price" class="col-sm-2">Telefoonnr</label>
            <div class="col-sm-4 required">
                <input type="text" class="form-control" name="telefoon" id="telefoon" placeholder="" autofocus value="<?php echo $klanttelefoon; ?>">
            </div>
        </div>


        <div class="form-group">
        <label for="regio" class="col-sm-2">Land</label>
        <div class="col-sm-4">
            <select name="land">
                <?php
                $STHregio = $DBH->prepare('SELECT * FROM tbllanden ORDER BY landnaam ASC');
                //vraag uitvoeren
                $STHregio->execute();
                $STHregio->setFetchMode(PDO::FETCH_OBJ);

                //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                while($row = $STHregio->fetch()){
                    $landid           = $row->landid;
                    $landnaam           = $row->landnaam;

                    //controleer of landid uit tbllanden = typeCafeID uit tblTypeCafe
                    //ja -> selected zetten
                    //nee -> niets doen
                    if($landid == $klantland ){
                        $selected = 'selected';
                    } else {
                        $selected = '';
                    }

                    echo '<option value="' . $landid . '" ' . $selected .'>' . $landnaam .'</option>';
                }
                ?>
            </select>
        </div>
            </div>

        <!-- bedrijfinfo -->

        <legend>Bedrijfsinfo (optioneel)</legend>
        <div class="form-group">
            <label for="firstname" class="col-sm-2">Bedrijfsnaam</label>
            <div class="col-sm-3 required">
                <input type="text" class="form-control" name="bedrijfsnaam" id="klantbedrijfsnaam" placeholder="" autofocus maxlength="100" value="<?php echo $klantbedrijf; ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="firstname" class="col-sm-2">BTW-nummer van bedrijf</label>
            <div class="col-sm-3 required">
                <input type="text" class="form-control" name="bedrijfbtw" id="klantbtw" placeholder="" autofocus maxlength="100" value="<?php echo $klantbtwnr; ?>">
            </div>
        </div>

        <!-- inloggegevens -->

        <legend>Inloggegevens</legend>
        <div class="form-group">
            <label for="stock" class="col-sm-2">Gebruikersnaam</label>
            <div class="col-sm-2 required">
                <input type="text" class="form-control" name="gebruikersnaam" id="klantgebruikersnaam" placeholder="" required autofocus value="<?php echo $klantgebruikersnaam; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="stock" class="col-sm-2">Wachtwoord</label>
            <div class="col-sm-2 required">
                <input type="text" class="form-control" name="password" id="klantpwd" placeholder="" required autofocus value="<?php echo $klantpwd; ?>">
            </div>
        </div>

        <legend>Online gegevens</legend>
        <div class="form-group">
            <label for="price" class="col-sm-2">Email</label>
            <div class="col-sm-4 required">
                <input type="text" class="form-control" name="email" id="klantmail" placeholder="" required autofocus value="<?php echo $klantmail; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="price" class="col-sm-2">Website</label>
            <div class="col-sm-4 required">
                <input type="text" class="form-control" name="website" id="klantwebsite" placeholder="" autofocus value="<?php echo $klantwebsite; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="price" class="col-sm-2">Registratiedatum</label>
            <div class="col-sm-2 required">
                <input type="text" class="form-control" name="klantenregistratiedatum" id="klantenregistratiedatum" placeholder="" required disabled autofocus value="<?php echo $klantregistratiedatum; ?>">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-4">
                <button type="submit" class="btn btn-block btn-primary" name="submit" id="submit">Klant wijzigen</button>
            </div>
        </div>
        <!--form sluiten-->
</form>
</div>

<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/chart.min.js"></script>
<script src="js/chart-data.js"></script>
<script src="js/easypiechart.js"></script>
<script src="js/easypiechart-data.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script>
    $('#calendar').datepicker({
    });

    !function ($) {
        $(document).on("click","ul.nav li.parent > a > span.icon", function(){
            $(this).find('em:first').toggleClass("glyphicon-minus");
        });
        $(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
    }(window.jQuery);

    $(window).on('resize', function () {
        if ($(window).width() > 768) $('#sidebar-collapse').collapse('show')
    })
    $(window).on('resize', function () {
        if ($(window).width() <= 767) $('#sidebar-collapse').collapse('hide')
    })
</script>
</body>

</html>
