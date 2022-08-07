<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>El Corcho</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/prettyPhoto.css" rel="stylesheet">
    <link href="css/price-range.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="images/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">
    <?php

    include_once('include/connection.php');
    session_start();

    if(isset($_GET['action'])){
        switch($_GET['action']){
            case "logout":
                unset($_SESSION['login']);
        }
    }



    if(isset($_POST['submit'])){

        $gebruikersnaam = $_SESSION['login'];

        $STH = $DBH->prepare('UPDATE tblklanten SET klantvnaam = :klantvnaam, klantfnaam = :klantfnaam, klantstraat = :klantstraat, 
klantpcode = :klantpcode, klantgemeente = :klantgemeente, klanttelefoon = :klanttelefoon, klantbedrijf = :klantbedrijf, 
klantbtwnr = :klantbtwnr, klantmail = :klantmail, klantpwd = :klantpwd, klantwebsite = :klantwebsite 
WHERE klantgebruikersnaam = :klantgebruikersnaam');

        $STH->bindParam(':klantgebruikersnaam', $gebruikersnaam);
        $STH->bindParam(':klantvnaam',$_POST['klantnaam']);
        $STH->bindParam(':klantfnaam',$_POST['klantfnaam']);
        $STH->bindParam(':klantstraat',$_POST['klantstraat']);
        $STH->bindParam(':klantpcode',$_POST['klantpcode']);
        $STH->bindParam(':klantgemeente',$_POST['klantgemeente']);
        $STH->bindParam(':klanttelefoon',$_POST['klanttelefoon']);
        $STH->bindParam(':klantbedrijf',$_POST['klantbedrijf']);
        $STH->bindParam(':klantbtwnr',$_POST['klantbtwnr']);
        $STH->bindParam(':klantmail',$_POST['klantmail']);
        $STH->bindParam(':klantpwd',$_POST['klantpwd']);
        $STH->bindParam(':klantwebsite',$_POST['klantwebsite']);

        $STH->execute();
    }


    ?>
</head><!--/head-->
<body>



<header id="header"><!--header-->
    <div class="header_top"><!--header_top-->
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="contactinfo">
                        <ul class="nav nav-pills">
                            <li><a href=""><i class="fa fa-phone"></i> 09 347 23 11</a></li>
                            <li><a href="mailto:info@elcorcho.be" target="_top"><i class="fa fa-envelope"></i> info@elcorcho.be</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="social-icons pull-right">
                        <ul class="nav navbar-nav">
                            <li><a href=""><i class="fa fa-facebook"></i></a></li>
                            <li><a href=""><i class="fa fa-twitter"></i></a></li>
                            <li><a href=""><i class="fa fa-linkedin"></i></a></li>
                            <li><a href=""><i class="fa fa-dribbble"></i></a></li>
                            <li><a href=""><i class="fa fa-google-plus"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div><!--/header_top-->

    <div class="header-middle"><!--header-middle-->
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <div class="logo pull-left">
                        <a href="index.php"><img src="images/home/logo.png" alt="" /></a>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="shop-menu pull-right">
                        <ul class="nav navbar-nav">

                            <?php
                            //link voor account
                            if(isset($_SESSION['login'])){
                                echo '<li><a href="account.php" class="active"><i class="fa fa-user"></i>';
                                echo ' ' . $_SESSION['login'];
                            } else {
                                echo '';
                            }
                            ?>
                            </a></li>

                            <?php
                            $sessionid = session_id();
                            $STH = $DBH->prepare('SELECT tbltijdelijkewinkelwagen.prijs, tbltijdelijkewinkelwagen.aantal FROM tbltijdelijkewinkelwagen INNER JOIN tblproducten ON tbltijdelijkewinkelwagen.productid = tblproducten.productid WHERE tbltijdelijkewinkelwagen.sessionid = :sessionid');
                            $STH->bindParam(':sessionid', $sessionid);
                            $STH->execute();

                            $STH->setFetchMode(PDO::FETCH_OBJ);

                            $totaal_winkelmandje = 0;

                            //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                            while ($row = $STH->fetch()) {

                                $prodprijs_winkelmandje = $row->prijs;
                                $prodaantal_winkelmandje = $row->aantal;

                                $totaalproduct_winkelmandje = ($prodprijs_winkelmandje * 1.21) * $prodaantal_winkelmandje;
                                $totaal_winkelmandje = $totaal_winkelmandje + $totaalproduct_winkelmandje;

                            }

                            //aantal verschillende producten
                            $aantal_winkelmandje = $STH->rowCount();

                            //als er geen in het winkelmandje zitten --> terugsturen
                            // als er wel in het mandje zitten --> doorsturen naar index.php
                            if($aantal_winkelmandje == 0){
                            } else {
                                echo '<li><a href="checkout.php"><i class="fa fa-crosshairs"></i> Checkout</a></li>';
                            }
                            ?>


                            <!--Link voor winkelwagentje-->
                            <?php
                            if(isset($aantal_winkelmandje)){
                                if($aantal_winkelmandje == 0) {
                                }else {
                                    echo '<li><a href="winkelwagentje.php"><i class="fa fa-shopping-cart"></i> Winkelwagentje ';
                                    echo '(' . $aantal_winkelmandje . ') (€'. round($totaal_winkelmandje,2) . ')';
                                }
                            }?>
                            </a></li>



                            <?php

                            //link voor login
                            if(!isset($_SESSION['login'])){
                                echo '<li><a href="login.php"><i class="fa fa-lock"></i> Login</a></li>';
                            } else {
                                echo '<li><a href="account_wijzigen.php?action=logout"><i class="fa fa-lock"></i> Afmelden</a></li>';
                            }

                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div><!--/header-middle-->

    <div class="header-bottom"><!--header-bottom-->
        <div class="container">
            <div class="row">
                <div class="col-sm-9">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div class="mainmenu pull-left">
                        <ul class="nav navbar-nav collapse navbar-collapse">
                            <li><a href="index.php?lang=nl">Home</a></li>
                            <li><a href="prod.php?lang=nl">Shop</a></li>
                            <li><a href="blog.php?lang=nl">Blog</a></li>
                            <li><a href="contact.php?lang=nl">Contact</a></li>
                            <li><a href="overons.php?lang=nl">Over ons</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-3">
                    <form action="prod.php" method="get">
                        <div class="search_box pull-right">
                            <input type="text" name="pn" placeholder="Product zoeken"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div><!--/header-bottom-->
</header><!--/header-->



<section>
    <div class="container">
        <div class="row">
            <div class="features_items"><!--features_items-->
                <div class="col-sm-12">
                    <div class="blog-post-area">
                        <h2 class="title text-center">Mijn Account</h2>

                        <?php
                        $STH = $DBH->prepare('SELECT * FROM tblklanten WHERE klantgebruikersnaam = :klantlogin');

                        $klantlogin = $_SESSION['login'];

                        $STH->bindParam(':klantlogin',$klantlogin);

                        $STH->execute();
                        $STH->setFetchMode(PDO::FETCH_OBJ);

                        $aantal = $STH -> rowCount();

                        if($aantal==0){
                        } else {
                            //methode van uitvoer
                            $STH->setFetchMode(PDO::FETCH_OBJ);

                            //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                            while($row = $STH->fetch()) {
                                $klantid = $row->klantid;
                                $klantnaam = $row->klantvnaam;
                                $klantfnaam = $row->klantfnaam;
                                $klantstraat = $row->klantstraat;
                                $klantpcode = $row->klantpcode;
                                $klantgemeente = $row->klantgemeente;
                                $klanttelefoon = $row->klanttelefoon;
                                $klantbedrijf = $row->klantbedrijf;
                                $klantbtwnr = $row->klantbtwnr;
                                $klantgebruikersnaam = $row->klantgebruikersnaam;
                                $klantpwd = $row->klantpwd;
                                $klantmail = $row->klantmail;
                                $klantwebsite = $row->klantwebsite;
                                $klantregistratiedatum = $row->klantregistratiedatum;
                            }
                            ?>

                            <div class="container-fluid">
                                <form class="form-horizontal" id="form_members" role="form" action="" enctype="multipart/form-data" method="POST"><br>

                                    <div class="form-group">
                                        <label class="col-sm-2">Familienaam:</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="klantfnaam" id="btwnummer" value="<?php echo $klantfnaam?>">
                                        </div>

                                        <label class="col-sm-2">Voornaam:</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="klantnaam" id="btwnummer" value="<?php echo $klantnaam?>">
                                        </div>

                                    </div>


                                    <div class="form-group">

                                        <label class="col-sm-2">Postcode:</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="klantpcode" id="btwnummer" value="<?php echo $klantpcode?>">
                                        </div>

                                        <label class="col-sm-2">Gemeente:</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="klantgemeente" id="btwnummer" value="<?php echo $klantgemeente?>">
                                        </div>

                                    </div>



                                    <div class="form-group">
                                        <label class="col-sm-2">Straat:</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="klantstraat" id="btwnummer" value="<?php echo $klantstraat?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2">Telefoonnummer:</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="klanttelefoon" id="btwnummer" value="<?php echo $klanttelefoon?>">
                                        </div>

                                        <label for="klantid" class="col-sm-2">Email-adres</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="klantmail" id="btwnummer" value="<?php echo $klantmail?>">
                                        </div>
                                    </div>


                                        <div class="form-group">
                                            <label class="col-sm-2">Naam van bedrijf:</label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" name="klantbedrijf" id="btwnummer" value="<?php echo $klantbedrijf?>">
                                            </div>

                                            <label class="col-sm-2">BTW-nummer:</label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" name="klantbtwnr" id="btwnummer" value="<?php echo $klantbtwnr?>">
                                            </div>
                                        </div>

                                    <div class="form-group">
                                        <label class="col-sm-2">Wachtwoord:</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="klantpwd" id="btwnummer" value="<?php echo $klantpwd?>">
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <div class="col-sm-3 text-right" >
                                            <button type="submit" class="btn btn-block btn-primary" name="submit" id="submit">Gegevens wijzigen</button>
                                        </div>
                                    </div>

                                </form>

                                <br><br><p><a href="verwijderklant.php?klantid=<?php echo $klantid?>" onclick="return confirm(\'Bent u zeker dat u uw account wilt verwijderen? Alle bestellingen zullen worden verwijderd. U kan dit niet meer ongedaan maken.\')">Uw account definitief verwijderen.</a></p>

                            </div>

                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
</section>


<footer id="footer"><!--Footer-->
    <div class="footer-widget">
        <div class="container">

            <div class="row">
                <div class="col-sm-2">
                    <div class="single-widget">
                        <h2>Sitemap</h2>
                        <ul class="nav nav-pills nav-stacked">
                            <li><a href="index.php?lang=nl">Index-pagina</a></li>
                            <li><a href="prod.php">Shop</a></li>
                            <li><a href="blog.php?lang=nl">Extra informatie</a></li>
                            <li><a href="contact.php?lang=nl">Contact</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="single-widget">
                        <h2>Handig</h2>
                        <ul class="nav nav-pills nav-stacked">
                            <li><a href="prod.php?st=1">Laatste stuks</a></li>
                            <li><a href="prod.php?promo=1">Onze promoties</a></li>
                            <li><a></a></li>
                            <li><a></a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="single-widget">
                        <h2>El Corcho</h2>
                        <ul class="nav nav-pills nav-stacked">
                            <li><a href="overons.php?lang=nl">Over ons</a></li>
                            <li><a href="contact.php?lang=nl">Contacteer ons</a></li>
                            <li><a></a></li>
                            <li><a></a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="single-widget">
                        <h2>Info</h2>
                        <ul class="nav nav-pills nav-stacked">
                            <li><a href="">Algemene voorwaarden</a></li>
                            <li><a href="">Gebruiksvoorwaarden</a></li>
                            <li><a href="">Privacy policy</a></li>
                            <li><a></a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="single-widget">
                        <h2>Service</h2>
                        <ul class="nav nav-pills nav-stacked">
                            <li><a>✓ Verzending via BPost</a></li>
                            <li><a>✓ Binnen 48 uur thuisbezorgd</a></li>
                            <li><a>✓ Kosteloos retourneren</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="single-widget">
                        <ul class="nav nav-pills nav-stacked">
                            <div class="single-widget">
                                <h2>Betaling</h2>
                                <img src="images/home/betaallogo.png">
                            </div>
                        </ul>
                    </div>
                </div>

            </div>

            <hr>

            <div class="row">

                <div class="col-sm-2 col-sm-offset-2">
                    <div class="single-widget">
                        <h2>Categorieën</h2>
                        <ul class="nav nav-pills nav-stacked">

                            <?php
                            $STH = $DBH->prepare('SELECT  COUNT(*) TotalCount, tblcategorie.* FROM tblproducten INNER JOIN tblcategorie ON tblproducten.categorieid = tblcategorie.categorieid GROUP BY tblcategorie.categorieid, tblcategorie.categorienaam ORDER BY TotalCount DESC LIMIT 4');
                            $STH->execute();
                            $STH->setFetchMode(PDO::FETCH_OBJ);
                            while($row = $STH->fetch()){
                                $categorieid = $row->categorieid;
                                $categorienaam = $row->categorienaam;

                                echo '<li><a href="prod.php?cat='.$categorieid.'">'.$categorienaam.'</a></li>';
                            }
                            ?>

                        </ul>
                    </div>
                </div>


                <div class="col-sm-2">
                    <div class="single-widget">
                        <h2>Wijnstreken</h2>
                        <ul class="nav nav-pills nav-stacked">

                            <?php
                            $STH = $DBH->prepare('SELECT  COUNT(*) TotalCount, tblregio.* FROM tblproducten INNER JOIN tblregio ON tblproducten.regioid = tblregio.regioid GROUP BY tblregio.regioid, tblregio.regionaam ORDER BY TotalCount DESC LIMIT 4');
                            $STH->execute();
                            $STH->setFetchMode(PDO::FETCH_OBJ);
                            while($row = $STH->fetch()){
                                $regioid = $row->regioid;
                                $regionaam = $row->regionaam;

                                echo '<li><a href="prod.php?reg='.$regioid.'">'.$regionaam.'</a></li>';
                            }
                            ?>

                        </ul>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="single-widget">
                        <h2>Producenten</h2>
                        <ul class="nav nav-pills nav-stacked">

                            <?php
                            $STH = $DBH->prepare('SELECT  COUNT(*) TotalCount, tblproducent.* FROM tblproducten INNER JOIN tblproducent ON tblproducten.producentid = tblproducent.producentid GROUP BY tblproducent.producentid, tblproducent.producentnaam ORDER BY TotalCount DESC LIMIT 4');
                            $STH->execute();
                            $STH->setFetchMode(PDO::FETCH_OBJ);
                            while($row = $STH->fetch()){
                                $producentid = $row->producentid;
                                $producentnaam = $row->producentnaam;

                                echo '<li><a href="prod.php?prod='.$producentid.'">'.$producentnaam.'</a></li>';
                            }
                            ?>

                        </ul>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="single-widget">
                        <h2>Druivenrassen</h2>
                        <ul class="nav nav-pills nav-stacked">

                            <?php
                            $STH = $DBH->prepare('SELECT  COUNT(*) TotalCount, tbldruiven.* FROM tblproducten INNER JOIN tbldruiven ON tblproducten.druifid = tbldruiven.druifid GROUP BY tbldruiven.druifid, tbldruiven.druifnaam ORDER BY TotalCount DESC LIMIT 4');
                            $STH->execute();
                            $STH->setFetchMode(PDO::FETCH_OBJ);
                            while($row = $STH->fetch()){
                                $druifid = $row->druifid;
                                $druifnaam = $row->druifnaam;

                                echo '<li><a href="prod.php?dru='.$druifid.'">'.$druifnaam.'</a></li>';
                            }
                            ?>

                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <p class="pull-left">Eindassessment Commercieel Webverkeer 2015-2016</p>
                <p class="pull-right">Geprogrammeerd door Dylan Cluyse van het <span><a target="_blank" href="http://www.hogerprofessioneelonderwijs.be">Hoger Professioneel Onderwijs</a></span></p>
            </div>
        </div>
    </div>

</footer><!--/Footer-->



<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

<!--scroll up-->
<script src="js/jquery.scrollUp.min.js"></script>
<!------------->

<script src="js/price-range.js"></script>
<script src="js/jquery.prettyPhoto.js"></script>
<script src="js/main.js"></script>



</body>
</html>