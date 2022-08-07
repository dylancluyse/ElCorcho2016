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
        <li><a href="klantlijst.php"><svg class="glyph stroked app-window"><use xlink:href="#stroked-app-window"></use></svg> Klanten</a></li>
        <li><a href="wijzigpwd.php"><svg class="glyph stroked app-window"><use xlink:href="#stroked-app-window"></use></svg> Wachtwoord veranderen</a></li>
    </ul>
</div><!--/.sidebar-->

<div class="col-sm-9 col-sm-offset-3 col-lg-9 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="index.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
            <li class="active">Producten</li>
        </ol>
    </div><!--/.row-->
    
        <?php
        $prodid = $_GET['productid'];

        $STH = $DBH->prepare('SELECT * FROM tblproducten WHERE productid = :productid');

        $STH->bindParam(':productid', $prodid);

        //vraag uitvoeren
        $STH->execute();

        $aantal = $STH -> rowCount();

        if($aantal==0){
        } else {
            //methode van uitvoer
            $STH->setFetchMode(PDO::FETCH_OBJ);

            //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
            while($row = $STH->fetch()){
                $productid     = $row->productid;
                $prodnaam      = $row->prodnaam;
                $prijs         = $row->prijs;
                $promotie      = $row->promotie;
                $voorraad      = $row->voorraad;
                $omschrijving  = $row->omschrijving;
                $prodsmaak     = $row->smaakid;
                $prodregio     = $row->regioid;
                $proddruif     = $row->druifid;
                $prodproducent = $row->producentid;
                $prodcat       = $row->categorieid;
                $prodinhoud    = $row->inhoud;
                $prodjaar      = $row->jaar;
            }
        }

        ?>

        <!--form voor het aanmaken van nieuwe smaken-->
        <form class="form-horizontal" id="form_members" role="form" action="" enctype="multipart/form-data" method="POST">
            <br><br>
            <div class="form-group">
                <label for="firstname" class="col-sm-2">Naam v/h product</label>
                <div class="col-sm-6 required">
                    <?php echo $prodnaam;?>
                </div>
            </div>

            <legend>Details</legend>
            <div class="form-group">
                <label for="cat" class="col-sm-2">Categorie</label>
                <div class="col-sm-4">
                    <?php
                    //categorienaam ophalen
                    $STHcat = $DBH->prepare('SELECT categorienaam FROM tblcategorie WHERE categorieid = :categorieid');
                    $STHcat->bindParam(':categorieid', $prodcat);

                    $STHcat->execute();
                    $STHcat->setFetchMode(PDO::FETCH_OBJ);
                    
                    while($row = $STHcat->fetch()){
                        $categorienaam = $row->categorienaam;
                    }
                    
                    //categorienaam weergeven
                    echo $categorienaam;
                    ?>
                </div>

                <label for="producent" class="col-sm-2">Producent</label>
                <div class="col-sm-4">
                    <?php
                    $STHproducent = $DBH->prepare('SELECT * FROM tblproducent WHERE producentid = :producentid ORDER BY producentnaam ASC');
                    $STHproducent->bindParam(':producentid', $prodproducent);

                    //vraag uitvoeren
                    $STHproducent->execute();
                    $STHproducent->setFetchMode(PDO::FETCH_OBJ);

                    //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                    while($row = $STHproducent->fetch()){
                        $producentid           = $row->producentid;
                        $producentnaam           = $row->producentnaam;

                        echo $producentnaam;
                    }
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label for="regio" class="col-sm-2">Regio</label>
                <div class="col-sm-4">
                    <?php
                    $STHregio = $DBH->prepare('SELECT * FROM tblregio WHERE regioid = :regioid ORDER BY regionaam ASC');
                    $STHregio->bindParam(':regioid', $prodregio);

                    //vraag uitvoeren
                    $STHregio->execute();
                    $STHregio->setFetchMode(PDO::FETCH_OBJ);

                    //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                    while($row = $STHregio->fetch()){
                        $regioid           = $row->regioid;
                        $regionaam           = $row->regionaam;

                        echo $regionaam;
                    }

                    ?>
                </div>

                <label for="smaak" class="col-sm-2">Smaakprofiel</label>
                <div class="col-sm-4">
                    <?php
                    $STHsmaak = $DBH->prepare('SELECT * FROM tblsmaakprofiel WHERE smaakid = :smaakid ORDER BY smaaknaam ASC');
                    $STHsmaak->bindParam(':smaakid', $prodsmaak);
                    //vraag uitvoeren
                    $STHsmaak->execute();
                    $STHsmaak->setFetchMode(PDO::FETCH_OBJ);

                    //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                    while($row = $STHsmaak->fetch()){
                        $smaakid             = $row->smaakid;
                        $smaaknaam           = $row->smaaknaam;

                        echo $smaaknaam;
                    }
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label for="druif" class="col-sm-2">Druifsoort</label>
                <div class="col-sm-4">
                    <?php
                    $STHdruiven = $DBH->prepare('SELECT * FROM tbldruiven WHERE druifid = :druifid ORDER BY druifnaam ASC');
                    $STHdruiven->bindParam(':druifid', $proddruif);

                    //vraag uitvoeren
                    $STHdruiven->execute();
                    $STHdruiven->setFetchMode(PDO::FETCH_OBJ);

                    //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                    while($row = $STHdruiven->fetch()){
                        $druifid           = $row->druifid;
                        $druifnaam         = $row->druifnaam;

                        echo $druifnaam;
                    }
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label for="inhoud" class="col-sm-2">Inhoud</label>
                <div class="col-sm-4">
                    <?php echo $prodinhoud . ' cl';?>
                </div>

                <label for="jaar" class="col-sm-2">Jaar</label>
                <div class="col-sm-4">
                    <?php echo $prodjaar;?>
                </div>
            </div>

            <legend>Omschrijving</legend>
            <div class="form-group">
                <label for="omschrijving" class="col-sm-2">Omschrijving</label>
                <div class="col-sm-10">
                    <?php echo $omschrijving; ?>
                </div>
            </div>
            <legend>Voorraad</legend>
            <div class="form-group">
                <label for="stock" class="col-sm-2">Stock</label>
                <div class="col-sm-2 required">
                    <?php echo $voorraad; ?>
                </div>
            </div>

            <legend>Prijs</legend>
            <div class="form-group">
                <label for="price" class="col-sm-2">Prijs</label>
                <div class="col-sm-2 required">
                    <?php echo $prijs; ?>
                </div>
            </div>
            <div class="form-group">
                <label for="promotion" class="col-sm-2">Promotie</label>
                <div class="col-sm-2 required">
                    <?php echo $promotie; ?>
                </div>
            </div>

            <legend>Foto's</legend>
            <?php
            $STH = $DBH->prepare('SELECT * FROM tblprodfoto WHERE productid = :productid');
            $STH->bindParam(':productid', $productid);
            $STH->execute();

            $aantal = $STH->rowCount();
            if($aantal==0){
                echo " Er werden geen foto's gevonden die gekoppeld zijn aan dit product.";
            } else {
                //methode van uitvoer
                $STH->setFetchMode(PDO::FETCH_OBJ);
                while ($row = $STH->fetch()) {
                    $fotoid   = $row->fotoid;
                    $filename = $row->fotonaam;
                    ?>
                    
                        <?php
                        echo '<img class="media_image" src="doc/' . $filename . '" width="200">';
                        ?>

                    <?php
                }
            }
            ?>

            <br>
        </form>
    </div>
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
