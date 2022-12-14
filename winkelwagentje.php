<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Winkelwagentje | El Corcho</title>
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
    session_start();
    include_once('include/connection.php');

    if(isset($_GET['action'])){
        switch($_GET['action']){
            case "logout":
                unset($_SESSION['login']);
        }
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
                                header('location:index.php?lang=nl');
                            } else {
                                echo '<li><a href="checkout.php"><i class="fa fa-crosshairs"></i> Checkout</a></li>';
                            }
                            ?>


                            <!--Link voor winkelwagentje-->
                            <?php
                            if(isset($aantal_winkelmandje)){
                                if($aantal_winkelmandje == 0) {
                                }else {
                                    echo '<li><a href="winkelwagentje.php" class="active"><i class="fa fa-shopping-cart"></i> Winkelwagentje ';
                                    echo '(' . $aantal_winkelmandje . ') (???'. round($totaal_winkelmandje,2) . ')';
                                }
                            }?>
                            </a></li>











                            <?php
                            //link voor login
                            if(!isset($_SESSION['login'])){
                                echo '<li><a href="login.php?lang=nl"><i class="fa fa-lock"></i> Login</a></li>';
                            } else {
                                echo '<li><a href="winkelwagentje.php?action=logout"><i class="fa fa-lock"></i> Afmelden</a></li>';
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

<section id="cart_items">
    <div class="container">
        <div class="breadcrumbs">
            <ol class="breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li class="active">Winkelwagentje</li>
            </ol>
        </div>
        <div class="table-responsive cart_info">
            <table class="table table-condensed">
                <thead>
                <tr class="cart_menu">
                    <td class="image">Product</td>
                    <td class="description"></td>
                    <td class="price">Prijs</td>
                    <td class="quantity">Hoeveelheid</td>
                    <td class="total">Totaal</td>
                    <td></td>
                </tr>
                </thead>
                <tbody>

                <?php

                $sessionid = session_id();
                $STH = $DBH->prepare('SELECT * FROM tblproducten INNER JOIN tbltijdelijkewinkelwagen ON tbltijdelijkewinkelwagen.productid = tblproducten.productid WHERE sessionid = :sessionid');
                $STH->bindParam(':sessionid', $sessionid);
                $STH->execute();
                $STH->setFetchMode(PDO::FETCH_OBJ);

                $subtotaal = "0";
                $totaalbtw = "0";

                while($row = $STH->fetch()){
                    $productid = $row->productid;
                    $prijs = $row->prijs;
                    $aantalprod = $row->aantal;
                    $naam = $row->prodnaam;
                    $promoprijs = $row->promotie;

                    if($promoprijs != "0"){
                        //subtotaal berekenen
                        $totaalproduct = $aantalprod * $promoprijs;
                        $productmetbtw = $aantalprod * ($promoprijs * 1.21);

                        $subtotaal = $subtotaal + $totaalproduct;
                        $totaalbtw = $totaalbtw + $productmetbtw;

                    } else {

                        //subtotaal berekenen
                        $totaalproduct = $aantalprod * $prijs;
                        $productmetbtw = $aantalprod * ($prijs * 1.21);

                        $subtotaal = $subtotaal + $totaalproduct;
                        $totaalbtw = $totaalbtw + $productmetbtw;
                        $btwprijs = $totaalbtw - $subtotaal;
                    }

                    $STHfoto = $DBH->prepare('SELECT * FROM tblprodfoto WHERE productid = :productid');
                    $STHfoto->bindParam(':productid',$productid);
                    $STHfoto->execute();
                    $STHfoto->setFetchMode(PDO::FETCH_OBJ);

                    $aantal = $STHfoto -> rowCount();

                    if($aantal==0){
                        $file = "nia.png";
                    } else {
                        while($row=$STHfoto->fetch()){
                            $file = $row->fotonaam;
                        }
                    }

                    ?>

                    <tr>
                        <td class="cart_product">
                            <a href="showprod.php?lang=nl&prod=<?php echo $productid?>"><img src="admin/doc/<?php echo $file?>" height="100" width="100" alt=""></a>
                        </td>
                        <td class="cart_description">
                            <?php echo '<h4><a href="showprod.php?lang=nl&prod='. $productid . '">' . $naam . '</a></h4>' ?>
                            <p>Product ID: <?php echo $productid;?></p>
                        </td>
                        <td class="cart_price">
                            <p><?php

                    if($promoprijs != "0"){
                        echo '???' . $promoprijs;
                    } else {
                        echo '???' . $prijs;
                    }

                                ?></p>
                        </td>
                        <td class="cart_quantity">
                            <div class="cart_quantity_button">
                                <a class="cart_quantity_up" href="mngwinkelwagen.php?action=upd&product=<?php echo $productid?>&aantal=<?php echo $aantalprod?>&wijziging=up"> + </a>
                                <input class="cart_quantity_input" type="text" name="quantity" value="<?php echo $aantalprod?>" autocomplete="off" size="2">
                                <a class="cart_quantity_down" href="mngwinkelwagen.php?action=upd&product=<?php echo $productid?>&aantal=<?php echo $aantalprod?>&wijziging=down"> - </a>
                            </div>
                        </td>
                        <td class="cart_total">
                            <p class="cart_total_price">
                                <?php
                                if($promoprijs != "0"){
                                    echo '???' . round($aantalprod * $promoprijs,2);
                                } else {
                                    echo '???' . round($aantalprod * $prijs,2);
                                }

                                ?></p>
                        </td>
                        <td class="cart_delete">
                            <a class="cart_quantity_delete" href="mngwinkelwagen.php?action=del&product=<?php echo $productid?>"><i class="fa fa-times"></i></a>
                        </td>
                    </tr>

                    <?php
                }
                ?>


                </tbody>
            </table>
        </div>
    </div>
</section> <!--/#cart_items-->

<section id="do_action">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 pull-right">
                <div class="total_area">
                    <ul>
                        <li>Totaal (excl. btw)<span><?php echo '???' . round($subtotaal,2);?></span></li>
                        <li>BTW-percentage<span>21 %</span></li>
                        <li>BTW<span><?php echo '??? ' .round($btwprijs,2)?></span></li>

                        <li>Totaal (incl btw)<span>
                            <?php
                            echo '???' . round($totaalbtw,2);
                            ?>
                        </span></li>
                    </ul>
                    <a class="btn btn-default update" href="mngwinkelwagen.php?action=del_all">Winkelmandje leegmaken</a>
                    <a class="btn btn-default check_out" href="checkout.php">Checkout</a>
                </div>
            </div>
        </div>
    </div>
</section><!--/#do_action-->

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
                            <li><a>??? Verzending via BPost</a></li>
                            <li><a>??? Binnen 48 uur thuisbezorgd</a></li>
                            <li><a>??? Kosteloos retourneren</a></li>
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
                        <h2>Categorie??n</h2>
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