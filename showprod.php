<?php
//
//
//
// taal : nederlands
//
//
if($_GET['lang'] == "nl") {

    ?>

    <!DOCTYPE html >
<html lang = "en" >
<head >
    <meta charset = "utf-8" >
    <meta name = "viewport" content = "width=device-width, initial-scale=1.0" >
    <meta name = "description" content = "" >
    <meta name = "author" content = "" >
    <title > El Corcho </title >
    <link href = "css/bootstrap.min.css" rel = "stylesheet" >
    <link href = "css/font-awesome.min.css" rel = "stylesheet" >
    <link href = "css/prettyPhoto.css" rel = "stylesheet" >
    <link href = "css/price-range.css" rel = "stylesheet" >
    <link href = "css/animate.css" rel = "stylesheet" >
    <link href = "css/main.css" rel = "stylesheet" >
    <link href = "css/responsive.css" rel = "stylesheet" >
    <!--[if lt IE 9]>
    <script src = "js/html5shiv.js" ></script >
    <script src = "js/respond.min.js" ></script >
    <![endif]-->
    <link rel = "shortcut icon" href = "images/ico/favicon.ico" >
    <link rel = "apple-touch-icon-precomposed" sizes = "144x144" href = "images/ico/apple-touch-icon-144-precomposed.png" >
    <link rel = "apple-touch-icon-precomposed" sizes = "114x114" href = "images/ico/apple-touch-icon-114-precomposed.png" >
    <link rel = "apple-touch-icon-precomposed" sizes = "72x72" href = "images/ico/apple-touch-icon-72-precomposed.png" >
    <link rel = "apple-touch-icon-precomposed" href = "images/ico/apple-touch-icon-57-precomposed.png" >

    <?php
    session_start();
    include_once('include/connection.php');
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
                            <a href="index.php"><img src="images/home/logo.png" alt=""/></a>
                        </div>
                        <div class="btn-group pull-right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle usa"
                                        data-toggle="dropdown">

                                    <?php

                                    if (isset($_GET['lang'])) {

                                        $lang = $_GET['lang'];

                                        if ($lang == "nl") {
                                            echo 'Nederlands';
                                        }

                                        if ($lang == "fr") {
                                            echo 'Frans';
                                        }

                                    } else {
                                    }
                                    ?>

                                    <span class="caret"></span></button>
                                <ul class="dropdown-menu">

                                    <?php

                                    if (isset($_GET['fotoid'])) {
                                        $fotoid = '&fotoid=' . $_GET['fotoid'];
                                    } else {
                                        $fotoid = '';
                                    }

                                    ?>

                                    <li><a href="showprod.php?lang=nl&prod=<?php echo $_GET['prod'] . $fotoid ?>">Nederlands
                                            (Néerlandais)</a></li>
                                    <li><a href="showprod.php?lang=fr&prod=<?php echo $_GET['prod'] . $fotoid ?>">Franstalig
                                            (Français)</a></li>
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
                                    echo '<li><a href="checkout.php"><i class="fa fa-crosshairs"></i> Checkout</a></li>';
                                }
                                ?>


                                <!--Link voor winkelwagentje-->
                                <?php
                                if (isset($aantal_winkelmandje)) {
                                    if ($aantal_winkelmandje == 0) {
                                    } else {
                                        echo '<li><a href="winkelwagentje.php"><i class="fa fa-shopping-cart"></i> Winkelwagentje ';
                                        echo '(' . $aantal_winkelmandje . ') (€' . round($totaal_winkelmandje, 2) . ')';
                                    }
                                } ?>
                                </a></li>


                                <?php
                                //link voor login
                                if (!isset($_SESSION['login'])) {
                                    echo '<li><a href="login.php?lang=nl"><i class="fa fa-lock"></i> Login</a></li>';
                                } else {
                                    echo '<li><a href="index.php?action=logout"><i class="fa fa-lock"></i> Afmelden</a></li>';
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


    <section>
        <div class="container">
            <div class="breadcrumbs">
                <ol class="breadcrumb">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="prod.php">Shop</a></li>
                    <?php
                    $STH = $DBH->prepare('SELECT prodnaam FROM tblproducten WHERE productid = :productid');
                    $STH->bindParam(':productid', $_GET['prod']);
                    $STH->execute();
                    $STH->setFetchMode(PDO::FETCH_OBJ);
                    while ($row = $STH->fetch()) {
                        $productnaam = $row->prodnaam;
                    };
                    ?>
                    <li class="active"><?php echo $productnaam ?></li>
                </ol>
            </div>

            <div class="row">
                <div class="col-sm-3">
                    <div class="left-sidebar">
                        <h2>Categorieen</h2>
                        <div class="panel-group category-products" id="accordian">

                            <?php
                            $STH = $DBH->prepare('SELECT  COUNT(*) TotalCount, tblcategorie.* FROM tblproducten INNER JOIN tblcategorie ON tblproducten.categorieid = tblcategorie.categorieid GROUP BY tblcategorie.categorieid, tblcategorie.categorienaam');

                            //vraag uitvoeren
                            $STH->execute();

                            //tellen hoeveel records er voldoen aan de vraag
                            $aantal = $STH->rowCount();

                            if ($aantal == 0) {
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
                                            echo '<h4 class="panel-title"><a href="prod.php?cat=' . $categorieid . '&lang=nl">' . $categorienaam . ' (' . $amount . ')</a></h4>';
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
                                        echo '<li><a href="prod.php?reg=' . $regioid . '">' . $regionaam . ' (' . $amount . ')</a></li>';
                                    }

                                    ?>

                                </ul>
                            </div>
                        </div><!--/brands_products-->
                    </div>
                </div>
                <?php

                $productid = $_GET['prod'];

                $STH = $DBH->prepare('SELECT * FROM tblproducten WHERE productid = :productid');

                $STH->bindParam(':productid', $productid);


                $STH->execute();
                $STH->setFetchMode(PDO::FETCH_OBJ);


                //tabel doorlopen en data eruit halen
                while ($row = $STH->fetch()) {
                    $productnaam = $row->prodnaam;
                    $prijs = $row->prijs;
                    $stock = $row->voorraad;
                    $druif = $row->druifid;
                    $producent = $row->producentid;
                    $omschrijving = $row->omschrijving;
                    $regioid = $row->regioid;
                    $smaak = $row->smaakid;
                    $jaar = $row->jaar;
                    $inhoud = $row->inhoud;


                    //toon de voorraad van dit product
                    switch ($stock) {
                        case $stock === 0:
                            $stock = "TE BESTELLEN";
                            $stockclass = "legevoorraad";
                            break;
                        case $stock <= 20;
                            $stock = "LAATSTE STUKS";
                            $stockclass = "laatstestuks";
                            break;
                        case $stock >= 20;
                            $stock = "OP VOORRAAD";
                            $stockclass = "opvoorraad";
                            break;
                    }


                    //toon dat het product wel of niet in promo staat
                    $promotiebedrag = $row->promotie;
                    if ($promotiebedrag == 0) {
                        $promotiebedrag = "";
                    } else {
                        $promotie = "PROMO";
                    }


                    ///////////////////////////////////////////////////
                    //ophalen naam van smaakprofiel uit tblsmaakprofiel
                    ///////////////////////////////////////////////////
                    $STHsmaak = $DBH->prepare('SELECT * FROM tblsmaakprofiel WHERE smaakid = :smid');

                    $STHsmaak->bindParam(':smid', $smaak);
                    //vraag uitvoeren

                    $STHsmaak->execute();
                    $STHsmaak->setFetchMode(PDO::FETCH_OBJ);

                    //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                    while ($row = $STHsmaak->fetch()) {
                        $smaaknaam = $row->smaaknaam;
                    }


                    ///////////////////////////////////////////////////
                    //ophalen naam van smaakprofiel uit tblsmaakprofiel
                    ///////////////////////////////////////////////////
                    $STHproducent = $DBH->prepare('SELECT * FROM tblproducent WHERE producentid = :prid');

                    $STHproducent->bindParam(':prid', $producent);
                    //vraag uitvoeren

                    $STHproducent->execute();
                    $STHproducent->setFetchMode(PDO::FETCH_OBJ);

                    //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                    while ($row = $STHproducent->fetch()) {
                        $producentnaam = $row->producentnaam;
                    }


                    ///////////////////////////////////////////////////
                    //ophalen naam van wijnstreek uit tblsmaakprofiel
                    ///////////////////////////////////////////////////
                    $STHregio = $DBH->prepare('SELECT * FROM tblregio WHERE regioid = :rid');
                    $STHregio->bindParam(':rid', $regioid);
                    //vraag uitvoeren
                    $STHregio->execute();
                    $STHregio->setFetchMode(PDO::FETCH_OBJ);

                    //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                    while ($row = $STHregio->fetch()) {
                        $regionaam = $row->regionaam;
                    }

                    ///////////////////////////////////////////////////
                    //ophalen naam van smaakprofiel uit tblsmaakprofiel
                    ///////////////////////////////////////////////////
                    $STHdruiven = $DBH->prepare('SELECT * FROM tbldruiven WHERE druifid = :drid');
                    $STHdruiven->bindParam(':drid', $druif);
                    //vraag uitvoeren
                    $STHdruiven->execute();
                    $STHdruiven->setFetchMode(PDO::FETCH_OBJ);

                    //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                    while ($row = $STHdruiven->fetch()) {
                        $druifnaam = $row->druifnaam;
                    }

                    ///////////////////////////////////////////////////
                    //ophalen fotonaam uit tblprodfoto
                    ///////////////////////////////////////////////////

                    $STHfoto = $DBH->prepare('SELECT * FROM tblprodfoto WHERE productid = :productid');
                    $STHfoto->bindParam(':productid', $productid);
                    $STHfoto->execute();
                    $STHfoto->setFetchMode(PDO::FETCH_OBJ);

                    $aantal = $STHfoto->rowCount();

                    if ($aantal == 0) {
                        $foto = "nia.png";
                    } else {
                        //methode van uitvoer
                        $STHfoto->setFetchMode(PDO::FETCH_OBJ);

                        //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                        while ($row = $STHfoto->fetch()) {
                            $foto = $row->fotonaam;
                        }
                    }
                }
                ?>

                <div class="col-sm-9 padding-right">
                    <div class="product-details"><!--product-details-->
                        <div class="col-sm-5">

                            <?php
                            //foto als hoofdfoto tonen
                            if (isset($_GET['fotoid'])) {
                                $STHfoto = $DBH->prepare('SELECT fotonaam FROM tblprodfoto WHERE fotoid = :fotoid');
                                $STHfoto->bindParam(':fotoid', $_GET['fotoid']);

                                $STHfoto->execute();
                                $STHfoto->setFetchMode(PDO::FETCH_OBJ);

                                while ($row = $STHfoto->fetch()) {
                                    $foto = $row->fotonaam;
                                }
                            }
                            ?>

                            <div class="view-product">
                                <img src="admin/doc/<?php echo $foto ?>">
                            </div>

                            <div id="similar-product" class="carousel slide" data-ride="carousel">

                                <div class="carousel-inner">
                                    <div class="item active">

                                        <?php
                                        $STHfoto = $DBH->prepare('SELECT * FROM tblprodfoto WHERE productid = :productid ');
                                        $STHfoto->bindParam(':productid', $productid);

                                        $STHfoto->execute();
                                        $STHfoto->setFetchMode(PDO::FETCH_OBJ);

                                        $aantal = $STHfoto->rowCount();

                                        if ($aantal == 0) {
                                            $foto = "nia.png";
                                        } else {
                                            //methode van uitvoer
                                            $STHfoto->setFetchMode(PDO::FETCH_OBJ);

                                            //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                                            while ($row = $STHfoto->fetch()) {
                                                $foto_all = $row->fotonaam;
                                                $id = $row->fotoid;
                                                echo '<a href="showprod.php?prod=' . $productid . '&fotoid=' . $id . '&lang=nl"><img src="admin/doc/' . $foto_all . '" width=100 height=100></a>';
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-7">
                            <div class="product-information"><!--/product-information-->
                                <h2><?php echo $productnaam; ?></h2>

                                <?php
                                if (isset($promotie)) {
                                    echo '<p class="promo">' . $promotie . '</p>';
                                }
                                ?>
                                <p>ProductID: <?php echo $productid; ?></p>
								<span><span><?php echo '€ ' . $prijs; ?></span>

                                    <form action=addtocart.php?productid=<?php echo $productid ?> method="post">

                                        <label>Aantal:</label>
                                        <input type="text" value="1" name="aantal"/></span>

                                <button type="submit" class="btn btn-default cart" name="submit">
                                    <i class="fa fa-shopping-cart"></i> Bestellen
                                </button>
                                </form>


                                <?php
                                echo '<p class="' . $stockclass . '"><br>' . $stock . '</p>';
                                echo '<p><b>Wijnstreek:</b> ' . $regionaam . '</p>';
                                echo '<p><b>Producent:</b> ' . $producentnaam . '</p>';
                                echo '<p><b>Smaakprofiel:</b> ' . $smaaknaam . '</p>';
                                echo '<p><b>Druifsoort:</b> ' . $druifnaam . '</p>';
                                echo '<p><b>Jaar:</b> ' . $jaar . '</p>';
                                echo '<p><b>Inhoud:</b> ' . $inhoud . 'cl</p></br>';
                                echo $omschrijving;
                                ?>

                            </div><!--/product-information-->
                        </div>
                    </div><!--/product-details-->


                    <div class="recommended_items"><!--recommended_items-->
                        <h2 class="title text-center">Meer wijnen uit <?php echo $regionaam ?></h2>

                        <div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner">
                                <div class="item active">


                                    <?php
                                    $STH = $DBH->prepare('SELECT * FROM tblprodfoto INNER JOIN tblproducten ON tblprodfoto.productid = tblproducten.productid WHERE regioid = :prodregio AND tblproducten.productid <> :productregio ORDER BY rand() LIMIT 3');
                                    $STH->bindParam(':prodregio', $regioid);
                                    $STH->bindParam(':productregio', $productid);
                                    $STH->execute();

                                    $STH->setFetchMode(PDO::FETCH_OBJ);

                                    $aantal = $STH->rowCount();

                                    if ($aantal == 0) {
                                    } else {
                                        $STH->setFetchMode(PDO::FETCH_OBJ);
                                        //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                                        while ($row = $STH->fetch()) {

                                            $prodid = $row->productid;
                                            $productnaam = $row->prodnaam;
                                            $productprijs = $row->prijs;
                                            $productpromo = $row->promotie;
                                            $fotonaam = $row->fotonaam;

                                            ?>

                                            <div class="col-sm-4">
                                                <div class="product-image-wrapper">
                                                    <div class="single-products">
                                                        <div class="productinfo text-center">

                                                            <img src="admin/doc/<?php echo $fotonaam ?>" alt=""/>
                                                            <h5><?php echo $productnaam ?></h5>
                                                            <p>
                                                                <?php
                                                                if ($productpromo == 0) {
                                                                    echo '€ ' . $productprijs;
                                                                } else {
                                                                    echo '€ ' . $productpromo . ' <s>€' . $productprijs . '</s>';
                                                                }
                                                                ?>
                                                            </p>
                                                        </div>
                                                        <?php

                                                        if ($productpromo == 0) {
                                                            echo '';
                                                        } else {
                                                            echo '<img src="images/home/sale.png" class="new" alt="" />';
                                                        }

                                                        ?>

                                                    </div>

                                                    <div class="choose">
                                                        <ul class="nav nav-pills nav-justified">
                                                            <?php
                                                            echo '<li><a href="showprod.php?prod=' . $prodid . '&lang=nl"><i class="fa fa-plus-square"></i>Product bekijken</a></li>';
                                                            ?>
                                                        </ul>
                                                    </div>

                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>


                                </div>
                            </div>
                        </div><!--/wijnen uit dezelfde wijnstreek-->
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
                            <h2>Wijnstreken</h2>
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
                            <h2>Producenten</h2>
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
                            <h2>Druivenrassen</h2>
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
                    <p class="pull-right">Geprogrammeerd door Dylan Cluyse van het <span><a target="_blank"
                                                                                            href="http://www.hogerprofessioneelonderwijs.be">Hoger Professioneel Onderwijs</a></span>
                    </p>
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
    <script>
        // Get the modal
        var modal = document.getElementById('myModal');

        // Get the image and insert it inside the modal - use its "alt" text as a caption
        var img = document.getElementById('myImg');
        var modalImg = document.getElementById("img01");
        var captionText = document.getElementById("caption");
        img.onclick = function () {
            modal.style.display = "block";
            modalImg.src = this.src;
            modalImg.alt = this.alt;
            captionText.innerHTML = this.alt;
        }

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function () {
            modal.style.display = "none";
        }
    </script>


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

    <!DOCTYPE html >
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <title> El Corcho </title>
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
                            <a href="index.php"><img src="images/home/logo.png" alt=""/></a>
                        </div>
                        <div class="btn-group pull-right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle usa"
                                        data-toggle="dropdown">

                                    <?php

                                    if (isset($_GET['lang'])) {

                                        $lang = $_GET['lang'];

                                        if ($lang == "nl") {
                                            echo 'Nederlands';
                                        }

                                        if ($lang == "fr") {
                                            echo 'Frans';
                                        }

                                    }

                                    ?>

                                    <span class="caret"></span></button>
                                <ul class="dropdown-menu">

                                    <?php

                                    if (isset($_GET['fotoid'])) {
                                        $fotoid = '&fotoid=' . $_GET['fotoid'];
                                    } else {
                                        $fotoid = '';
                                    }

                                    ?>

                                    <li><a href="showprod.php?lang=nl&prod=<?php echo $_GET['prod'] . $fotoid ?>">Nederlands
                                            (Néerlandais)</a></li>
                                    <li><a href="showprod.php?lang=fr&prod=<?php echo $_GET['prod'] . $fotoid ?>">Franstalig
                                            (Français)</a></li>
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

                                    //gegevens ophalen uit winkelmandje
                                    $prodprijs_winkelmandje = $row->prijs;
                                    $prodaantal_winkelmandje = $row->aantal;

                                    //totaalbedrag incl. btw tonen bij samenvatting van winkelmandje
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
                                    echo '<li><a href="index.php?action=logout"><i class="fa fa-lock"></i> Se déconnecter</a></li>';
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
                                <input type="text" name="pn" placeholder="Chercher un produit"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div><!--/header-bottom-->
    </header><!--/header-->


    <section>
        <div class="container">
            <div class="breadcrumbs">
                <ol class="breadcrumb">
                    <li><a href="index.php">Page d'accueil</a></li>
                    <li><a href="prod.php">Boutique</a></li>
                    <?php
                    $STH = $DBH->prepare('SELECT prodnaam FROM tblproducten WHERE productid = :productid');
                    $STH->bindParam(':productid', $_GET['prod']);
                    $STH->execute();
                    $STH->setFetchMode(PDO::FETCH_OBJ);
                    while ($row = $STH->fetch()) {
                        $productnaam = $row->prodnaam;
                    };
                    ?>
                    <li class="active"><?php echo $productnaam ?></li>
                </ol>
            </div>

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
                            $aantal = $STH->rowCount();

                            if ($aantal == 0) {
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
                                            echo '<h4 class="panel-title"><a href="prod.php?cat=' . $categorieid . '&lang=nl">' . $categorienaam . ' (' . $amount . ')</a></h4>';
                                            ?>
                                        </div>
                                    </div>

                                    <?php
                                }
                            }
                            ?>
                        </div><!--/category-products-->

                        <div class="brands_products"><!--brands_products-->
                            <h2>Régions</h2>
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
                                        echo '<li><a href="prod.php?reg=' . $regioid . '">' . $regionaam . ' (' . $amount . ')</a></li>';
                                    }

                                    ?>

                                </ul>
                            </div>
                        </div><!--/brands_products-->
                    </div>
                </div>
                <?php

                $productid = $_GET['prod'];

                $STH = $DBH->prepare('SELECT * FROM tblproducten WHERE productid = :productid');

                $STH->bindParam(':productid', $productid);


                $STH->execute();
                $STH->setFetchMode(PDO::FETCH_OBJ);


                //tabel doorlopen en data eruit halen
                while ($row = $STH->fetch()) {
                    $productnaam = $row->prodnaam;
                    $prijs = $row->prijs;
                    $stock = $row->voorraad;
                    $druif = $row->druifid;
                    $producent = $row->producentid;
                    $omschrijving = $row->omschrijving;
                    $regioid = $row->regioid;
                    $smaak = $row->smaakid;
                    $jaar = $row->jaar;
                    $inhoud = $row->inhoud;


                    //toon de voorraad van dit product
                    switch ($stock) {
                        case $stock === 0:
                            $stock = "PAS EN STOCK";
                            $stockclass = "legevoorraad";
                            break;
                        case $stock <= 20;
                            $stock = "LES DERNIÈRES PIÈCES";
                            $stockclass = "laatstestuks";
                            break;
                        case $stock >= 20;
                            $stock = "EN STOCK";
                            $stockclass = "opvoorraad";
                            break;
                    }


                    //toon dat het product wel of niet in promo staat
                    $promotiebedrag = $row->promotie;
                    if ($promotiebedrag == 0) {
                        $promotiebedrag = "";
                    } else {
                        $prijs = $promotiebedrag;
                        $promotie = "PROMO";
                    }


                    ///////////////////////////////////////////////////
                    //ophalen naam van smaakprofiel uit tblsmaakprofiel
                    ///////////////////////////////////////////////////
                    $STHsmaak = $DBH->prepare('SELECT * FROM tblsmaakprofiel WHERE smaakid = :smid');

                    $STHsmaak->bindParam(':smid', $smaak);
                    //vraag uitvoeren

                    $STHsmaak->execute();
                    $STHsmaak->setFetchMode(PDO::FETCH_OBJ);

                    //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                    while ($row = $STHsmaak->fetch()) {
                        $smaaknaam = $row->smaaknaam;
                    }


                    ///////////////////////////////////////////////////
                    //ophalen naam van smaakprofiel uit tblsmaakprofiel
                    ///////////////////////////////////////////////////
                    $STHproducent = $DBH->prepare('SELECT * FROM tblproducent WHERE producentid = :prid');

                    $STHproducent->bindParam(':prid', $producent);
                    //vraag uitvoeren

                    $STHproducent->execute();
                    $STHproducent->setFetchMode(PDO::FETCH_OBJ);

                    //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                    while ($row = $STHproducent->fetch()) {
                        $producentnaam = $row->producentnaam;
                    }


                    ///////////////////////////////////////////////////
                    //ophalen naam van wijnstreek uit tblsmaakprofiel
                    ///////////////////////////////////////////////////
                    $STHregio = $DBH->prepare('SELECT * FROM tblregio WHERE regioid = :rid');
                    $STHregio->bindParam(':rid', $regioid);
                    //vraag uitvoeren
                    $STHregio->execute();
                    $STHregio->setFetchMode(PDO::FETCH_OBJ);

                    //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                    while ($row = $STHregio->fetch()) {
                        $regionaam = $row->regionaam;
                    }

                    ///////////////////////////////////////////////////
                    //ophalen naam van smaakprofiel uit tblsmaakprofiel
                    ///////////////////////////////////////////////////
                    $STHdruiven = $DBH->prepare('SELECT * FROM tbldruiven WHERE druifid = :drid');
                    $STHdruiven->bindParam(':drid', $druif);
                    //vraag uitvoeren
                    $STHdruiven->execute();
                    $STHdruiven->setFetchMode(PDO::FETCH_OBJ);

                    //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                    while ($row = $STHdruiven->fetch()) {
                        $druifnaam = $row->druifnaam;
                    }

                    ///////////////////////////////////////////////////
                    //ophalen fotonaam uit tblprodfoto
                    ///////////////////////////////////////////////////

                    $STHfoto = $DBH->prepare('SELECT * FROM tblprodfoto WHERE productid = :productid');
                    $STHfoto->bindParam(':productid', $productid);
                    $STHfoto->execute();
                    $STHfoto->setFetchMode(PDO::FETCH_OBJ);

                    $aantal = $STHfoto->rowCount();

                    if ($aantal == 0) {
                        $foto = "nia.png";
                    } else {
                        //methode van uitvoer
                        $STHfoto->setFetchMode(PDO::FETCH_OBJ);

                        //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                        while ($row = $STHfoto->fetch()) {
                            $foto = $row->fotonaam;
                        }
                    }
                }
                ?>

                <div class="col-sm-9 padding-right">
                    <div class="product-details"><!--product-details-->
                        <div class="col-sm-5">

                            <?php
                            //foto als hoofdfoto tonen
                            if (isset($_GET['fotoid'])) {
                                $STHfoto = $DBH->prepare('SELECT fotonaam FROM tblprodfoto WHERE fotoid = :fotoid');
                                $STHfoto->bindParam(':fotoid', $_GET['fotoid']);

                                $STHfoto->execute();
                                $STHfoto->setFetchMode(PDO::FETCH_OBJ);

                                while ($row = $STHfoto->fetch()) {
                                    $foto = $row->fotonaam;
                                }
                            }
                            ?>

                            <div class="view-product">
                                <img src="admin/doc/<?php echo $foto ?>">
                            </div>

                            <div id="similar-product" class="carousel slide" data-ride="carousel">

                                <div class="carousel-inner">
                                    <div class="item active">

                                        <?php
                                        $STHfoto = $DBH->prepare('SELECT * FROM tblprodfoto WHERE productid = :productid ');
                                        $STHfoto->bindParam(':productid', $productid);

                                        $STHfoto->execute();
                                        $STHfoto->setFetchMode(PDO::FETCH_OBJ);

                                        $aantal = $STHfoto->rowCount();

                                        if ($aantal == 0) {
                                            $foto = "nia.png";
                                        } else {
                                            //methode van uitvoer
                                            $STHfoto->setFetchMode(PDO::FETCH_OBJ);

                                            //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                                            while ($row = $STHfoto->fetch()) {
                                                $foto_all = $row->fotonaam;
                                                $id = $row->fotoid;
                                                echo '<a href="showprod.php?prod=' . $productid . '&fotoid=' . $id . '&lang=fr"><img src="admin/doc/' . $foto_all . '" width=100 height=100></a>';
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-7">
                            <div class="product-information"><!--/product-information-->
                                <h2><?php echo $productnaam; ?></h2>

                                <?php
                                if (isset($promotie)) {
                                    echo '<p class="promo">' . $promotie . '</p>';
                                }
                                ?>
                                <p>ID produit: <?php echo $productid; ?></p>
								<span><span><?php echo '€ ' . $prijs; ?></span>

                                    <form action=addtocart.php?productid=<?php echo $productid ?> method="post">

                                        <label>Quantité:</label>
                                        <input type="text" value="1" name="aantal"/></span>

                                <button type="submit" class="btn btn-default cart" name="submit">
                                    <i class="fa fa-shopping-cart"></i> COMMANDER
                                </button>
                                </form>


                                <?php
                                echo '<p class="' . $stockclass . '"><br>' . $stock . '</p>';
                                echo '<p><b>Région:</b> ' . $regionaam . '</p>';
                                echo '<p><b>Vitriculteur:</b> ' . $producentnaam . '</p>';
                                echo '<p><b>Profil de saveur:</b> ' . $smaaknaam . '</p>';
                                echo '<p><b>Cépage:</b> ' . $druifnaam . '</p>';
                                echo '<p><b>Année:</b> ' . $jaar . '</p>';
                                echo '<p><b>Volume:</b> ' . $inhoud . 'cl</p></br>';
                                echo $omschrijving;
                                ?>

                            </div><!--/product-information-->
                        </div>
                    </div><!--/product-details-->


                    <div class="recommended_items"><!--recommended_items-->
                        <h2 class="title text-center">Plus de vins de <?php echo $regionaam ?></h2>

                        <div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner">
                                <div class="item active">


                                    <?php
                                    $STH = $DBH->prepare('SELECT * FROM tblprodfoto INNER JOIN tblproducten ON tblprodfoto.productid = tblproducten.productid WHERE regioid = :prodregio AND tblproducten.productid <> :productregio ORDER BY rand() LIMIT 3');
                                    $STH->bindParam(':prodregio', $regioid);
                                    $STH->bindParam(':productregio', $productid);
                                    $STH->execute();

                                    $STH->setFetchMode(PDO::FETCH_OBJ);

                                    $aantal = $STH->rowCount();

                                    if ($aantal == 0) {
                                    } else {
                                        $STH->setFetchMode(PDO::FETCH_OBJ);
                                        //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                                        while ($row = $STH->fetch()) {

                                            $prodid = $row->productid;
                                            $productnaam = $row->prodnaam;
                                            $productprijs = $row->prijs;
                                            $productpromo = $row->promotie;
                                            $fotonaam = $row->fotonaam;

                                            ?>

                                            <div class="col-sm-4">
                                                <div class="product-image-wrapper">
                                                    <div class="single-products">
                                                        <div class="productinfo text-center">

                                                            <img src="admin/doc/<?php echo $fotonaam ?>" alt=""/>
                                                            <h5><?php echo $productnaam ?></h5>
                                                            <p>
                                                                <?php
                                                                if ($productpromo == 0) {
                                                                    echo '€ ' . $productprijs;
                                                                } else {
                                                                    echo '€ ' . $productpromo . ' <s>€' . $productprijs . '</s>';
                                                                }
                                                                ?>
                                                            </p>
                                                        </div>
                                                        <?php

                                                        if ($productpromo == 0) {
                                                            echo '';
                                                        } else {
                                                            echo '<img src="images/home/sale.png" class="new" alt="" />';
                                                        }

                                                        ?>

                                                    </div>

                                                    <div class="choose">
                                                        <ul class="nav nav-pills nav-justified">
                                                            <?php
                                                            echo '<li><a href="showprod.php?prod=' . $prodid . '&lang=fr"><i class="fa fa-plus-square"></i>Regarder la produit</a></li>';
                                                            ?>
                                                        </ul>
                                                    </div>

                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>


                                </div>
                            </div>
                        </div><!--/wijnen uit dezelfde wijnstreek-->
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
                                <li><a href="blog.php?lang=nl">Informations supplémentaires</a></li>
                                <li><a href="contact.php?lang=nl">Contact</a></li>
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
                                <li><a href="overons.php?lang=nl">Qui sommes-nous?</a></li>
                                <li><a href="contact.php?lang=nl">Contactez-nous</a></li>
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
                            <h2>Producteur</h2>
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
                    <p class="pull-right">Codé par Dylan Cluyse de l' <span><a target="_blank"
                                                                                            href="http://www.hogerprofessioneelonderwijs.be">Hoger Professioneel Onderwijs</a> à Courtrai</span>
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
    <script>
        // Get the modal
        var modal = document.getElementById('myModal');

        // Get the image and insert it inside the modal - use its "alt" text as a caption
        var img = document.getElementById('myImg');
        var modalImg = document.getElementById("img01");
        var captionText = document.getElementById("caption");
        img.onclick = function () {
            modal.style.display = "block";
            modalImg.src = this.src;
            modalImg.alt = this.alt;
            captionText.innerHTML = this.alt;
        }

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function () {
            modal.style.display = "none";
        }
    </script>


    </body>
    </html>

    <?php
} else {
    header('location:prod.php');
}
