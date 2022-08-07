<?php

if(isset($_GET['action'])){
    switch($_GET['action']){
        case "logout":
            unset($_SESSION['login']);
    }
}

if(isset($_SESSION['login'])){
    header('location:account.php');
}

//
//
//
// taal : nederlands
//
//
if($_GET['lang'] == "nl") {

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Login | El Corcho</title>
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
    <script src="js/main.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="images/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">
    <?php
    include_once('include/connection.php');
    session_start();
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
                    <div class="btn-group pull-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle usa" data-toggle="dropdown">

                                <?php

                                if(isset($_GET['lang'])) {

                                    $lang = $_GET['lang'];

                                    if ($lang == "nl") {
                                        echo 'Nederlands';
                                    }

                                    if ($lang == "fr") {
                                        echo 'Frans';
                                    }

                                } else {
                                    header('location:login.php?lang=nl');
                                }
                                ?>

                                <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li><a href="login.php?lang=nl">Nederlands (Néerlandais)</a></li>
                                <li><a href="login.php?lang=fr">Franstalig (Français)</a></li>
                            </ul>
                        </div>

                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="shop-menu pull-right">
                        <ul class="nav navbar-nav">

                            <?php
                            //link voor account
                            if(isset($_SESSION['login'])){
                                echo '<li><a href="account.php"><i class="fa fa-user"></i>';
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
                                echo '<li><a href="login.php?lang=nl"><i class="fa fa-lock"></i> Login</a></li>';
                            } else {
                                echo '<li><a href="login.php?lang=nl&action=logout"><i class="fa fa-lock"></i> Afmelden</a></li>';
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


<section><!--form-->
    <div class="container">
        <div class="row">
            <div class="col-sm-4 col-sm-offset-1">
                <div class="login-form"><!--login form-->
                    <h2>Al een account?</h2>

                    <?php
                    if (isset($_SESSION['errorpwd'])){
                        $error = $_SESSION['errorpwd'];
                        echo '<p class="error">' . $error . '</p><br>';
                        unset($_SESSION['errorpwd']);
                    }
                    ?>

                    <form action="logincontrole.php" method="post">
                        <input type="text" placeholder="gebruikersnaam" name="login"/>
                        <input type="password" placeholder="wachtwoord" name="password"/>
                        <button type="submit" class="btn btn-default" name="btnlogin">Inloggen</button>
                    </form>
                </div><!--/login form-->
            </div>

            <div class="col-sm-1">
                <h2 class="or">OF</h2>
            </div>

            <div class="col-lg-6">

                <div class="signup-form"><!--sign up form-->
                    <h2>Nieuwe gebruiker?</h2>


                    <?php
                    //foutmelding geven indien al gebruikte username en/of gebruikte email adres
                    if (isset($_SESSION['error'])){
                        $error = $_SESSION['error'];
                        echo '<p class="error">' .$error . '</p><br>';
                        unset($_SESSION['error']);
                    }
                    ?>


                    <form action="aanmakenklant.php" method="post">

                        <input type="text"  name="fnaam"    required="true" placeholder="naam"/>
                        <input type="text"  name="vnaam"    required="true" placeholder="voornaam"/>
                        <input type="email" name="email"    required="true" placeholder="email-adres"/>

                        <input type="text"  name="adres"    placeholder="adres"/>
                        <input type="text"  name="pcode"    placeholder="postcode"/>
                        <input type="text"  name="gemeente" placeholder="gemeente"/>

                        <select name="land">
                            <?php
                            $STHlanden = $DBH->prepare('SELECT * FROM tbllanden ORDER BY landnaam ASC');

                            //vraag uitvoeren
                            $STHlanden->execute();

                            $STHlanden->setFetchMode(PDO::FETCH_OBJ);

                            //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                            while($row = $STHlanden->fetch()){

                                $landid             = $row->landid;
                                $landnaam           = $row->landnaam;

                                echo '<option value="'.$landid.'">'.$landnaam.'</option>';
                            }
                            ?>
                        </select><br><br>

                        <input type="text" name="telefoon" placeholder="telefoonnummer"/>

                        <input type="text" name="gebruikersnaam" required="true" placeholder="gebruikersnaam"/>

                        <input type="password" name="password" required="true" placeholder="wachtwoord"/>

                        <input type="text" name="website" placeholder="website"/>

                        <br>

                        Optioneel:

                        <br><br>

                        <input type="text" name="bedrijfsnaam" placeholder="bedrijfsnaam">

                        <input type="text" name="btw" placeholder="btw-nummer van bedrijf"/>

                        <button type="submit" class="btn btn-default">Registreren</button>
                    </form><br><br>
                </div><!--/sign up form-->
            </div>
        </div>
    </div>
</section><!--/form-->


<footer id="footer"><!--Footer-->
    <div class="footer-widget">
        <div class="container">

            <div class="row">
                <div class="col-sm-2">
                    <div class="single-widget">
                        <h2>Sitemap</h2>
                        <ul class="nav nav-pills nav-stacked">
                            <li><a href="index.php?lang=nl">Index</a></li>
                            <li><a href="prod.php">Shop</a></li>
                            <li><a href="blog.php">Extra informatie</a></li>
                            <li><a href="contact.php">Contact</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="single-widget">
                        <h2>Handig</h2>
                        <ul class="nav nav-pills nav-stacked">
                            <li><a href="prod.php?st=1">Laatste stuks</a></li>
                            <li><a href="prod.php?promo=1">Onze promoties</a></li>
                            <li><a>*</a></li>
                            <li><a>*</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="single-widget">
                        <h2>El Corcho</h2>
                        <ul class="nav nav-pills nav-stacked">
                            <li><a href="overons.php?lang=nl">Over ons</a></li>
                            <li><a href="contact.php?lang=nl">Contacteer ons</a></li>
                            <li><a>*</a></li>
                            <li><a>*</a></li>
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
                            <li><a>*</a></li>
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
<script src="js/jquery.scrollUp.min.js"></script>
<script src="js/price-range.js"></script>
<script src="js/jquery.prettyPhoto.js"></script>
<script src="js/main.js"></script>



</body>
</html>




















































    <?php
    //
    //
    //taal : frans
    //
    //
} elseif($_GET['lang'] == "fr") {
    ?>


    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Login | El Corcho</title>
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
        <script src="js/main.js"></script>
        <![endif]-->
        <link rel="shortcut icon" href="images/ico/favicon.ico">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">
        <?php
        include_once('include/connection.php');
        session_start();
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
                                <li><a href="mailto:info@elcorcho.be" target="_top"><i class="fa fa-envelope"></i>
                                        info@elcorcho.be</a></li>
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
                            <a href="index.php?lang=fr"><img src="images/home/logo.png" alt=""/></a>
                        </div>
                        <div class="btn-group pull-right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle usa"
                                        data-toggle="dropdown">
                                    Frans
                                    <span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li><a href="login.php?lang=nl">Nederlands (Néerlandais)</a></li>
                                    <li><a href="login.php?lang=fr">Franstalig (Français)</a></li>
                                </ul>
                            </div>

                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="shop-menu pull-right">
                            <ul class="nav navbar-nav">

                                <?php
                                //link voor account
                                if (isset($_SESSION['login'])) {
                                    echo '<li><a href="account.php"><i class="fa fa-user"></i>';
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
                                if ($aantal_winkelmandje == 0) {
                                } else {
                                    echo '<li><a href="checkout.php"><i class="fa fa-crosshairs"></i> Check-out</a></li>';
                                }
                                ?>


                                <!--Link voor winkelwagentje-->
                                <?php
                                if (isset($aantal_winkelmandje)) {
                                    if ($aantal_winkelmandje == 0) {
                                    } else {
                                        echo '<li><a href="winkelwagentje.php"><i class="fa fa-shopping-cart"></i> Panier ';
                                        echo '(' . $aantal_winkelmandje . ') (€' . round($totaal_winkelmandje, 2) . ')';
                                    }
                                } ?>
                                </a></li>


                                <?php

                                //link voor login
                                if (!isset($_SESSION['login'])) {
                                    echo '<li><a href="login.php?lang=fr"><i class="fa fa-lock"></i> Se connecter</a></li>';
                                } else {
                                    echo '<li><a href="login.php?lang=fr&action=logout"><i class="fa fa-lock"></i> Se déconnecter</a></li>';
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
                            <button type="button" class="navbar-toggle" data-toggle="collapse"
                                    data-target=".navbar-collapse">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                        </div>
                        <div class="mainmenu pull-left">
                            <ul class="nav navbar-nav collapse navbar-collapse">
                                <li><a href="index.php?lang=fr">Page d'accueil</a></li>
                                <li><a href="prod.php">Boutique</a></li>
                                <li><a href="blog.php">Blog</a></li>
                                <li><a href="contact.php">Contact</a></li>
                                <li><a href="overons.php">Qui sommes-nous?</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <form action="prod.php" method="get">
                            <div class="search_box pull-right">
                                <input type="text" name="pn" placeholder="Chercher un produit"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div><!--/header-bottom-->
    </header><!--/header-->


    <section><!--form-->
        <div class="container">
            <div class="row">
                <div class="col-sm-4 col-sm-offset-1">
                    <div class="login-form"><!--login form-->
                        <h2>Déjà client?</h2>

                        <?php
                        if (isset($_SESSION['errorpwd'])) {
                            $error = $_SESSION['errorpwd'];
                            echo '<p class="error">' . $error . '</p><br>';
                            unset($_SESSION['errorpwd']);
                        }
                        ?>

                        <form action="logincontrole.php" method="post">
                            <input type="text" placeholder="nom d'utilisateur" name="login"/>
                            <input type="password" placeholder="mot de passe" name="password"/>
                            <button type="submit" class="btn btn-default" name="btnlogin">Se connecter</button>
                        </form>
                    </div><!--/login form-->
                </div>

                <div class="col-sm-1">
                    <h2 class="or">OU</h2>
                </div>

                <div class="col-lg-6">

                    <div class="signup-form"><!--sign up form-->
                        <h2>Nouveau client?</h2>


                        <?php
                        //foutmelding geven indien al gebruikte username en/of gebruikte email adres
                        if (isset($_SESSION['error'])) {
                            $error = $_SESSION['error'];
                            echo '<p class="error">' . $error . '</p><br>';
                            unset($_SESSION['error']);
                        }
                        ?>


                        <form action="aanmakenklant.php" method="post">

                            <input type="text" name="fnaam" required="true" placeholder="nom"/>
                            <input type="text" name="vnaam" required="true" placeholder="prénom"/>
                            <input type="email" name="email" required="true" placeholder="adresse e-mail"/>

                            <input type="text" name="adres" placeholder="adresse"/>
                            <input type="text" name="pcode" placeholder="code postal"/>
                            <input type="text" name="gemeente" placeholder="commune"/>

                            <select name="land">
                                <?php
                                $STHlanden = $DBH->prepare('SELECT * FROM tbllanden ORDER BY landnaam ASC');

                                //vraag uitvoeren
                                $STHlanden->execute();

                                $STHlanden->setFetchMode(PDO::FETCH_OBJ);

                                //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                                while ($row = $STHlanden->fetch()) {

                                    $landid = $row->landid;
                                    $landnaam = $row->landnaam;

                                    echo '<option value="' . $landid . '">' . $landnaam . '</option>';
                                }
                                ?>
                            </select><br><br>

                            <input type="text" name="telefoon" placeholder="numéro de téléphone"/>

                            <input type="text" name="gebruikersnaam" required="true" placeholder="nom d'utilisateur"/>

                            <input type="password" name="password" required="true" placeholder="mot de passe"/>

                            <input type="text" name="website" placeholder="site web"/>

                            <br>
                            Optionnel:
                            <br><br>
                            <input type="text" name="bedrijfsnaam" placeholder="nom d'entreprise">

                            <input type="text" name="btw" placeholder="numéro de tva"/>

                            <button type="submit" class="btn btn-default">Enregistrer</button>
                        </form>
                        <br><br>
                    </div><!--/sign up form-->
                </div>
            </div>
        </div>
    </section><!--/form-->


    <footer id="footer"><!--Footer-->
        <div class="footer-widget">
            <div class="container">

                <div class="row">
                    <div class="col-sm-2">
                        <div class="single-widget">
                            <h2>Plan du site</h2>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="index.php?lang=fr">Page d'accueil</a></li>
                                <li><a href="prod.php">Boutique</a></li>
                                <li><a href="blog.php">Informations supplémentaires</a></li>
                                <li><a href="contact.php">Contact</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="single-widget">
                            <h2>Utile</h2>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="prod.php?st=1">Dernières bouteilles</a></li>
                                <li><a href="prod.php?promo=1">Nos promotions</a></li>
                                <li><a></a></li>
                                <li><a></a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="single-widget">
                            <h2>El Corcho</h2>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="overons.php">Qui sommes-nous?</a></li>
                                <li><a href="contact.php">Contactez nous</a></li>
                                <li><a></a></li>
                                <li><a></a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="single-widget">
                            <h2>Info</h2>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="">Conditions générales</a></li>
                                <li><a href="">Conditions d'utilisation</a></li>
                                <li><a href="">Politique de confidentialité</a></li>
                                <li><a></a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="single-widget">
                            <h2>Service</h2>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a>✓ Envoyé par BPost</a></li>
                                <li><a>✓ Livré à domicile entre 48h</a></li>
                                <li><a>✓ Retourner gratuitement</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="single-widget">
                            <ul class="nav nav-pills nav-stacked">
                                <div class="single-widget">
                                    <h2>Paiement</h2>
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
                            <h2>Catégories</h2>
                            <ul class="nav nav-pills nav-stacked">

                                <?php
                                $STH = $DBH->prepare('SELECT  COUNT(*) TotalCount, tblcategorie.* FROM tblproducten INNER JOIN tblcategorie ON tblproducten.categorieid = tblcategorie.categorieid GROUP BY tblcategorie.categorieid, tblcategorie.categorienaam ORDER BY TotalCount DESC LIMIT 4');
                                $STH->execute();
                                $STH->setFetchMode(PDO::FETCH_OBJ);
                                while ($row = $STH->fetch()) {
                                    $categorieid = $row->categorieid;
                                    $categorienaam = $row->categorienaam;

                                    echo '<li><a href="prod.php?cat=' . $categorieid . '">' . $categorienaam . '</a></li>';
                                }
                                ?>

                            </ul>
                        </div>
                    </div>


                    <div class="col-sm-2">
                        <div class="single-widget">
                            <h2>Régions</h2>
                            <ul class="nav nav-pills nav-stacked">

                                <?php
                                $STH = $DBH->prepare('SELECT  COUNT(*) TotalCount, tblregio.* FROM tblproducten INNER JOIN tblregio ON tblproducten.regioid = tblregio.regioid GROUP BY tblregio.regioid, tblregio.regionaam ORDER BY TotalCount DESC LIMIT 4');
                                $STH->execute();
                                $STH->setFetchMode(PDO::FETCH_OBJ);
                                while ($row = $STH->fetch()) {
                                    $regioid = $row->regioid;
                                    $regionaam = $row->regionaam;

                                    echo '<li><a href="prod.php?reg=' . $regioid . '">' . $regionaam . '</a></li>';
                                }
                                ?>

                            </ul>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="single-widget">
                            <h2>Producteurs</h2>
                            <ul class="nav nav-pills nav-stacked">

                                <?php
                                $STH = $DBH->prepare('SELECT  COUNT(*) TotalCount, tblproducent.* FROM tblproducten INNER JOIN tblproducent ON tblproducten.producentid = tblproducent.producentid GROUP BY tblproducent.producentid, tblproducent.producentnaam ORDER BY TotalCount DESC LIMIT 4');
                                $STH->execute();
                                $STH->setFetchMode(PDO::FETCH_OBJ);
                                while ($row = $STH->fetch()) {
                                    $producentid = $row->producentid;
                                    $producentnaam = $row->producentnaam;

                                    echo '<li><a href="prod.php?prod=' . $producentid . '">' . $producentnaam . '</a></li>';
                                }
                                ?>

                            </ul>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="single-widget">
                            <h2>Des cépages</h2>
                            <ul class="nav nav-pills nav-stacked">

                                <?php
                                $STH = $DBH->prepare('SELECT  COUNT(*) TotalCount, tbldruiven.* FROM tblproducten INNER JOIN tbldruiven ON tblproducten.druifid = tbldruiven.druifid GROUP BY tbldruiven.druifid, tbldruiven.druifnaam ORDER BY TotalCount DESC LIMIT 4');
                                $STH->execute();
                                $STH->setFetchMode(PDO::FETCH_OBJ);
                                while ($row = $STH->fetch()) {
                                    $druifid = $row->druifid;
                                    $druifnaam = $row->druifnaam;

                                    echo '<li><a href="prod.php?dru=' . $druifid . '">' . $druifnaam . '</a></li>';
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
                    <p class="pull-right">Codé par Dylan Cluyse de l'<span><a target="_blank"
                                                                                            href="http://www.hogerprofessioneelonderwijs.be">Hoger Professioneel Onderwijs</a></span>
                    </p>
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

    <?php
}


if(!isset($_GET['lang'])){
    header('location:login.php?lang=nl');
}
?>