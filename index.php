<?php
session_start();
include_once('include/connection.php');

if(isset($_GET['action'])){
    switch($_GET['action']){
        case "logout":
            unset($_SESSION['login']);
    }
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
    <title>El Corcho</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/prettyPhoto.css" rel="stylesheet">
    <link href="css/price-range.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
    <![endif]-->
    <link rel="shortcut icon" href="images/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">

    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
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
                                    header('location:index.php?lang=nl');
                                }
                                ?>

                                <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li><a href="index.php?lang=nl">Nederlands / Néerlandais</a></li>
                                <li><a href="index.php?lang=fr">Franstalig / Français</a></li>
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
                                echo '<li><a href="index.php?action=logout&lang=nl"><i class="fa fa-lock"></i> Afmelden</a></li>';
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
                            <li><a href="index.php?lang=nl" class="active">Home</a></li>
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


<section id="slider"><!--slider-->
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div id="slider-carousel" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#slider-carousel" data-slide-to="0" class="active"></li>
                        <li data-target="#slider-carousel" data-slide-to="1"></li>
                    </ol>

                    <div class="carousel-inner">
                        <div class="item active">
                            <div class="col-sm-6">
                                <h1><span>La vita é bella!</span></h1>
                                <h2>Tot 20% korting op alle Siciliaanse wijnen!</h2>
                                <p></p>
                                <a type="button" class="btn btn-default update" href="prod.php?reg=17">Bekijk ze nu!</a>
                            </div>
                            <div class="col-sm-6">
                                <img src="images/home/home_1.jpg" class="girl img-responsive" alt="" />
                            </div>
                        </div>

                        <div class="item">
                            <div class="col-sm-6">
                                <h1><span>Blackhawk goes Italy!</span></h1>
                                <h2>De sensationeel lekker nieuwe Blackhawk is binnen!</h2>
                                <a type="button" class="btn btn-default update" href="showprod.php?prod=61&lang=fr">Bestel ze nu!</a>
                            </div>
                            <div class="col-sm-6">
                                <img src="images/home/home_2.jpg" class="girl img-responsive" alt="" />
                            </div>
                        </div>
                    </div>

                    <a href="#slider-carousel" class="left control-carousel hidden-xs" data-slide="prev">
                        <i class="fa fa-angle-left"></i>
                    </a>
                    <a href="#slider-carousel" class="right control-carousel hidden-xs" data-slide="next">
                        <i class="fa fa-angle-right"></i>
                    </a>
                </div>

            </div>
        </div>
    </div>
</section><!--/slider-->

<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Subscribe our Newsletter</h4>
            </div>
            <div class="modal-body">
                <p>Subscribe to our mailing list to get the latest updates straight in your inbox.</p>
                <form>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" placeholder="Email Address">
                    </div>
                    <button type="submit" class="btn btn-primary">Subscribe</button>
                </form>
            </div>
        </div>
    </div>
</div>

<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <div class="left-sidebar">
                    <h2>Categorieën</h2>
                    <div class="panel-group category-products" id="accordian">

                        <?php
                        $STH = $DBH->prepare('SELECT  COUNT(*) TotalCount, tblcategorie.* FROM tblproducten INNER JOIN tblcategorie ON tblproducten.categorieid = tblcategorie.categorieid GROUP BY tblcategorie.categorieid, tblcategorie.categorienaam');

                        //vraag uitvoeren
                        $STH->execute();

                        //tellen hoeveel records er voldoen aan de vraag
                        $aantal = $STH -> rowCount();

                        if($aantal==0){
                        } else {
                            //methode van uitvoer
                            $STH->setFetchMode(PDO::FETCH_OBJ);

                            //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                            while ($row = $STH->fetch()) {

                                $categorieid = $row->categorieid;
                                $categorienaam = $row->categorienaam;
                                $amount = $row->TotalCount;
                                ?>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <?php
                                        echo '<h4 class="panel-title"><a href="prod.php?cat=' . $categorieid . '&lang=nl">' . $categorienaam . ' (' . $amount .')</a></h4>';
                                        ?>
                                    </div>
                                </div>

                                <?php
                            }
                        }
                        ?>
                    </div><!--/category-products-->

                    <div class="brands_products"><!--brands_products-->
                        <h2>Wijnstreken</h2>
                        <div class="brands-name">
                            <ul class="nav nav-pills nav-stacked">

                                <?php
                                $STH = $DBH->prepare('SELECT  COUNT(*) TotalCount, tblregio.* FROM tblproducten INNER JOIN tblregio ON tblproducten.regioid = tblregio.regioid GROUP BY tblregio.regioid, tblregio.regionaam');
                                $STH->execute();
                                $STH->setFetchMode(PDO::FETCH_OBJ);

                                while ($row = $STH->fetch()) {
                                    $regioid = $row->regioid;
                                    $regionaam = $row->regionaam;
                                    $amount = $row->TotalCount;
                                    echo '<li><a href="prod.php?reg=' . $regioid . '">' . $regionaam. ' (' . $amount .')</a></li>';
                                }

                                ?>

                            </ul>
                        </div>
                    </div><!--/brands_products-->
                </div>
            </div>

            <div class="col-sm-9 padding-right">
                <div class="features_items"><!--features_items-->
                    <h2 class="title text-center">Aanbevolen producten</h2>
                                <?php

                                $STH = $DBH->prepare('SELECT  * FROM tblproducten WHERE promotie <> 0 AND voorraad <> 0 ORDER BY rand() LIMIT 6');
                                $STH->execute();
                                $STH->setFetchMode(PDO::FETCH_OBJ);

                                while ($row = $STH->fetch()) {

                                    $promo        = $row->promotie;
                                    $productid    = $row->productid;
                                    $productnaam  = $row->prodnaam;
                                    $productprijs = $row->prijs;

                                    //in promo? --> promoprijs + doorstreepte normale prijs tonen//
                                    if($promo == 0){
                                        $productprijs = '€' . $productprijs;
                                    } else {
                                        $productprijs = ' €' . $promo .' <s>€' . $productprijs . '</s>' ;
                                    }



                                    $STHfoto = $DBH->prepare('SELECT * FROM tblprodfoto WHERE productid = :productid');
                                    $STHfoto->bindParam(':productid',$productid);
                                    $STHfoto->execute();

                                    $aantal = $STHfoto -> rowCount();

                                    //geen foto beschikbaar? --> "nia.png" tonen//
                                    if($aantal==0){
                                        $foto = "nia.png";
                                    } else {
                                        //methode van uitvoer
                                        $STHfoto->setFetchMode(PDO::FETCH_OBJ);

                                        //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                                        while($row = $STHfoto->fetch()){
                                            $foto = $row->fotonaam;
                                        }
                                    }
                                    ?>
                                <div class="col-sm-4">
                                <div class="product-image-wrapper">
                                    <div class="single-products">
                                        <div class="productinfo text-center">
                                            <?php

                                            echo '<img src="admin/doc/' . $foto .'" alt="" width="250" height="250"/>';

                                            ?>

                                <h2>
                                    <?php
                                    //originele prijs doorstrepen --> promoprijs tonen
                                    echo $productprijs;
                                    ?>
                                </h2>
                                <p>
                                    <?php
                                    //als productnaam te lang is --> afkorten
                                    $afgekorteproductnaam = (strlen($productnaam) > 20) ? substr($productnaam, 0, 20) . '...' : $productnaam;
                                    echo $afgekorteproductnaam;
                                    ?>
                                </p>
                            </div>
                                        <div class="product-overlay">
                                            <div class="overlay-content">
                                                <form action=toevoegenaanwinkelmandje.php?productid=<?php echo $productid ?> method="post">
                                                    <h2><?php echo $productprijs ?></h2>
                                                    <p><?php echo $productnaam ?></p>
                                                    <label>Aantal:</label><input type="text" value="1" name="aantal"/>
                                                    <button type="submit" class="btn btn-default cart" name="submit">
                                                        <i class="fa fa-shopping-cart"></i>Aan winkelkarretje toevoegen.
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="choose">
                                        <ul class="nav nav-pills nav-justified">
                                            <?php
                                            echo '<li><a href="showprod.php?prod=' . $productid .'&lang=nl"><i class="fa fa-plus-square"></i>Product bekijken</a></li>';
                                            ?>
                                        </ul>
                                    </div>


                                </div>
                </div>
                                <?php     } ?>
                </div><!--features_items-->
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
    <title>El Corcho</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/prettyPhoto.css" rel="stylesheet">
    <link href="css/price-range.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
    <![endif]-->
    <link rel="shortcut icon" href="images/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">

    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
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
                        <a href="index.php?lang=fr"><img src="images/home/logo.png" alt="" /></a>
                    </div>
                    <div class="btn-group pull-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle usa" data-toggle="dropdown">

Français
                                <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li><a href="index.php?lang=nl">Nederlands / Néerlandais</a></li>
                                <li><a href="index.php?lang=fr">Franstalig / Français</a></li>
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
                                echo '<li><a href="checkout.php"><i class="fa fa-crosshairs"></i> Check-out</a></li>';
                            }
                            ?>


                            <!--Link voor winkelwagentje-->
                            <?php
                            if(isset($aantal_winkelmandje)){
                                if($aantal_winkelmandje == 0) {
                                }else {
                                    echo '<li><a href="winkelwagentje.php"><i class="fa fa-shopping-cart"></i> Panier ';
                                    echo '(' . $aantal_winkelmandje . ') (€'. round($totaal_winkelmandje,2) . ')';
                                }
                            }?>
                            </a></li>





                            <?php

                            //link voor login
                            if(!isset($_SESSION['login'])){
                                echo '<li><a href="login.php?lang=fr"><i class="fa fa-lock"></i> Se connecter</a></li>';
                            } else {
                                echo '<li><a href="index.php?action=logout&lang=fr"><i class="fa fa-lock"></i> Se déconnecter</a></li>';
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
                            <li><a href="index.php?lang=fr" class="active">Page d'accueil</a></li>
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


<section id="slider"><!--slider-->
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div id="slider-carousel" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#slider-carousel" data-slide-to="0" class="active"></li>
                        <li data-target="#slider-carousel" data-slide-to="1"></li>
                    </ol>

                    <div class="carousel-inner">
                        <div class="item active">
                            <div class="col-sm-6">
                                <h1><span>La vita é bella!</span></h1>
                                <h2>Jusqu'à 20% de réduction sur tous les vins Siciliens!</h2>
                                <a type="button" class="btn btn-default update" href="prod.php?reg=17">Commandez maintenant!</a>
                            </div>
                            <div class="col-sm-6">
                                <img src="images/home/home_1.jpg" class="girl img-responsive" alt="" />
                            </div>
                        </div>
                        <div class="item">
                            <div class="col-sm-6">
                                <h1><span>Blackhawk goes Italy!</span></h1>
                                <h2>La sensationelle savoureuse nouvelle Blackhawk</h2>
                                <a type="button" class="btn btn-default update" href="showprod.php?prod=61&lang=fr">Commandez maintenant!</a>
                            </div>
                            <div class="col-sm-6">
                                <img src="images/home/home_2.jpg" class="girl img-responsive" alt="" />
                            </div>
                        </div>
                    </div>

                    <a href="#slider-carousel" class="left control-carousel hidden-xs" data-slide="prev">
                        <i class="fa fa-angle-left"></i>
                    </a>
                    <a href="#slider-carousel" class="right control-carousel hidden-xs" data-slide="next">
                        <i class="fa fa-angle-right"></i>
                    </a>
                </div>

            </div>
        </div>
    </div>
</section><!--/slider-->

<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Subscribe our Newsletter</h4>
            </div>
            <div class="modal-body">
                <p>Subscribe to our mailing list to get the latest updates straight in your inbox.</p>
                <form>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" placeholder="Email Address">
                    </div>
                    <button type="submit" class="btn btn-primary">Subscribe</button>
                </form>
            </div>
        </div>
    </div>
</div>

<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <div class="left-sidebar">
                    <h2>Catégories</h2>
                    <div class="panel-group category-products" id="accordian">

                        <?php
                        $STH = $DBH->prepare('SELECT  COUNT(*) TotalCount, tblcategorie.* FROM tblproducten INNER JOIN tblcategorie ON tblproducten.categorieid = tblcategorie.categorieid GROUP BY tblcategorie.categorieid, tblcategorie.categorienaam');

                        //vraag uitvoeren
                        $STH->execute();

                        //tellen hoeveel records er voldoen aan de vraag
                        $aantal = $STH -> rowCount();

                        if($aantal==0){
                        } else {
                            //methode van uitvoer
                            $STH->setFetchMode(PDO::FETCH_OBJ);

                            //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                            while ($row = $STH->fetch()) {

                                $categorieid = $row->categorieid;
                                $categorienaam = $row->categorienaam;
                                $amount = $row->TotalCount;
                                ?>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <?php
                                        echo '<h4 class="panel-title"><a href="prod.php?cat=' . $categorieid . '&lang=nl">' . $categorienaam . ' (' . $amount .')</a></h4>';
                                        ?>
                                    </div>
                                </div>

                                <?php
                            }
                        }
                        ?>
                    </div><!--/category-products-->

                    <div class="brands_products"><!--brands_products-->
                        <h2>Des régions</h2>
                        <div class="brands-name">
                            <ul class="nav nav-pills nav-stacked">

                                <?php
                                $STH = $DBH->prepare('SELECT  COUNT(*) TotalCount, tblregio.* FROM tblproducten INNER JOIN tblregio ON tblproducten.regioid = tblregio.regioid GROUP BY tblregio.regioid, tblregio.regionaam');
                                $STH->execute();
                                $STH->setFetchMode(PDO::FETCH_OBJ);

                                while ($row = $STH->fetch()) {
                                    $regioid = $row->regioid;
                                    $regionaam = $row->regionaam;
                                    $amount = $row->TotalCount;
                                    echo '<li><a href="prod.php?reg=' . $regioid . '">' . $regionaam. ' (' . $amount .')</a></li>';
                                }

                                ?>

                            </ul>
                        </div>
                    </div><!--/brands_products-->
                </div>
            </div>

            <div class="col-sm-9 padding-right">
                <div class="features_items"><!--features_items-->
                    <h2 class="title text-center">Produits recommandés</h2>
                    <?php

                    $STH = $DBH->prepare('SELECT  * FROM tblproducten WHERE promotie <> 0 AND voorraad <> 0 ORDER BY rand() LIMIT 6');
                    $STH->execute();
                    $STH->setFetchMode(PDO::FETCH_OBJ);

                    while ($row = $STH->fetch()) {

                        $promo        = $row->promotie;
                        $productid    = $row->productid;
                        $productnaam  = $row->prodnaam;
                        $productprijs = $row->prijs;

                        //in promo? --> promoprijs + doorstreepte normale prijs tonen//
                        if($promo == 0){
                            $productprijs = '€' . $productprijs;
                        } else {
                            $productprijs = ' €' . $promo .' <s>€' . $productprijs . '</s>' ;
                        }



                        $STHfoto = $DBH->prepare('SELECT * FROM tblprodfoto WHERE productid = :productid');
                        $STHfoto->bindParam(':productid',$productid);
                        $STHfoto->execute();

                        $aantal = $STHfoto -> rowCount();

                        //geen foto beschikbaar? --> "nia.png" tonen//
                        if($aantal==0){
                            $foto = "nia.png";
                        } else {
                            //methode van uitvoer
                            $STHfoto->setFetchMode(PDO::FETCH_OBJ);

                            //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                            while($row = $STHfoto->fetch()){
                                $foto = $row->fotonaam;
                            }
                        }
                        ?>
                        <div class="col-sm-4">
                            <div class="product-image-wrapper">
                                <div class="single-products">
                                    <div class="productinfo text-center">
                                        <?php

                                        echo '<img src="admin/doc/' . $foto .'" alt="" width="250" height="250"/>';

                                        ?>

                                        <h2>
                                            <?php
                                            //originele prijs doorstrepen --> promoprijs tonen
                                            echo $productprijs;
                                            ?>
                                        </h2>
                                        <p>
                                            <?php
                                            //als productnaam te lang is --> afkorten
                                            $afgekorteproductnaam = (strlen($productnaam) > 20) ? substr($productnaam, 0, 20) . '...' : $productnaam;
                                            echo $afgekorteproductnaam;
                                            ?>
                                        </p>
                                    </div>
                                    <div class="product-overlay">
                                        <div class="overlay-content">
                                            <form action=toevoegenaanwinkelmandje.php?productid=<?php echo $productid ?> method="post">
                                                <h2><?php echo $productprijs ?></h2>
                                                <p><?php echo $productnaam ?></p>
                                                <label>Quantité:</label><input type="text" value="1" name="aantal"/>
                                                <button type="submit" class="btn btn-default cart" name="submit">
                                                    <i class="fa fa-shopping-cart"></i> Ajouter au panier
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="choose">
                                    <ul class="nav nav-pills nav-justified">
                                        <?php
                                        echo '<li><a href="showprod.php?prod=' . $productid .'&lang=fr"><i class="fa fa-plus-square"></i>Voir la produit</a></li>';
                                        ?>
                                    </ul>
                                </div>


                            </div>
                        </div>
                    <?php     } ?>
                </div><!--features_items-->
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
                        <h2>Plan du site</h2>
                        <ul class="nav nav-pills nav-stacked">
                            <li><a href="index.php?lang=nl">Page d'accueil</a></li>
                            <li><a href="prod.php">Boutique</a></li>
                            <li><a href="blog.php?lang=nl">Information supplémentaires</a></li>
                            <li><a href="contact.php?lang=nl">Contact</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="single-widget">
                        <h2>Handig</h2>
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
                            <li><a href="overons.php?lang=nl">Qui sommes-nous?</a></li>
                            <li><a href="contact.php?lang=nl">Contactez nous</a></li>
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
                            <li><a>✓ Envoyé par Bpost</a></li>
                            <li><a>✓ Livré à domicile entre 48 heures</a></li>
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
                        <h2>Des catégories</h2>
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
                        <h2>Régions de vin</h2>
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
                        <h2>Producteurs</h2>
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
                        <h2>Des cépages</h2>
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
                <p class="pull-right">Codé par Dylan Cluyse de l'<span><a target="_blank" href="http://www.hogerprofessioneelonderwijs.be">Hoger Professioneel Onderwijs</a></span></p>
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
    header('location:index.php?lang=nl');
}
