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
        <li class="parent">
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
        <li class="active"><a href="bestellingen.php"><svg class="glyph stroked app-window"><use xlink:href="#stroked-app-window"></use></svg> Bestellingen</a></li>
        <li><a href="klantlijst.php"><svg class="glyph stroked app-window"><use xlink:href="#stroked-app-window"></use></svg> Klanten</a></li>
        <li><a href="wijzigpwd.php"><svg class="glyph stroked app-window"><use xlink:href="#stroked-app-window"></use></svg> Wachtwoord veranderen</a></li>
    </ul>
</div><!--/.sidebar-->

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href=""><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
            <li class="active">Bestelling</li>
        </ol>
    </div><!--/.row-->
    
    <div class="container">
        <?php

        if(isset($_POST['submit'])){
            $status = $_POST['status'];
            $bestellingid = $_GET['bestellingid'];

            //via named placeholders
            $STH = $DBH->prepare('UPDATE tblbestellingen SET statusid = :statusid WHERE bestellingid = :bestellingid');

            //named placeholders aanmaken
            $STH->bindParam(':statusid', $status);
            $STH->bindParam(':bestellingid', $bestellingid);

            //query/vraag uitvoeren
            $STH->execute();

        }


        ?>
    </div>






<div class="container-fluid">
    <form class="form-horizontal" id="form_members" role="form" action="" enctype="multipart/form-data" method="POST"><br>

        <?php
        $bestellingid = $_GET['bestellingid'];

        $STH = $DBH->prepare('SELECT * FROM tblbestellingen INNER JOIN tblklanten ON tblklanten.klantid = tblbestellingen.klantid WHERE bestellingid = :bestellingid');

        $STH->bindParam(':bestellingid', $bestellingid);
        $STH->execute();
        $STH->setFetchMode(PDO::FETCH_OBJ);

        while ($row = $STH->fetch()) {
        $bestellingid = $row->bestellingid;
        $klantid = $row->klantid;
        $klantvnaam = $row->klantvnaam;
        $klantfnaam = $row->klantfnaam;
        $leveringid = $row->leveringsid;
        $betaalid = $row->betaalid;
        $datumtijd = $row->datumtijd;
        $statusid = $row->statusid;
        $adres = $row->adres;


        $STHbet = $DBH->prepare('SELECT * FROM tblbetaalwijze WHERE betaalid = :betaalid');
        $STHbet->bindParam(':betaalid', $betaalid);
        $STHbet->execute();
        $STHbet->setFetchMode(PDO::FETCH_OBJ);

            while($row=$STHbet->fetch()){
                $betaalwijze = $row->betaalwnaam;
            }
        }
        ?>
        
        <div class="form-group">
            <label for="bestellingid" class="col-sm-2">BestellingID</label>
            <div class="col-sm-4">
                <?php echo $bestellingid?>
            </div>

            <label for="klantid" class="col-sm-2">KlantID</label>
            <div class="col-sm-4">
                <?php echo '<a href="showklant.php?klantid='.$klantid.'">'. $klantid . '</a>'?>
            </div>
        </div>



        <div class="form-group">
            <label for="naam" class="col-sm-2">Naam + voornaam</label>
            <div class="col-sm-4">
                <?php echo $klantfnaam . ' '. $klantvnaam . '<br>'?>
            </div>

            <label for="betaalid" class="col-sm-2">Betaalwijze</label>
            <div class="col-sm-4">
                <?php echo $betaalwijze . '<br>'?>
            </div>
        </div>



        <div class="form-group">
            <label for="leveringid" class="col-sm-2">Leveringswijze</label>
            <div class="col-sm-4">
                <?php echo $leveringid . '<br>'?>
             </div>

            <label for="adres" class="col-sm-2">Leveringsadres</label>
            <div class="col-sm-4">
                <?php echo $adres . '<br>';?>
            </div>
        </div>



        <div class="form-group">
            <label for="datum" class="col-sm-2">Datum</label>
            <div class="col-sm-4">
                <?php echo $datumtijd . '<br>';?>
            </div>
        </div>



        <div class="form-group">
            <label for="datum" class="col-sm-2">Producten</label>
            <div class="col-sm-4">
        <?php

        $STH = $DBH->prepare('SELECT tblprodperbestelling.productid, tblproducten.prodnaam, tblprodperbestelling.prijs, tblprodperbestelling.aantal FROM tblprodperbestelling INNER JOIN tblproducten ON tblproducten.productid = tblprodperbestelling.productid WHERE bestellingid = :bestellingid');
        $STH->bindParam(':bestellingid', $bestellingid);
        $STH->execute();
        $STH->setFetchMode(PDO::FETCH_OBJ);

        while($row=$STH->fetch()){
            $productid = $row->productid;
            $productnaam = $row->prodnaam;
            $productprijs = $row->prijs;
            $productaantal = $row->aantal;

            echo $productid.' - '.$productnaam. ' - €'. $productprijs .'</br>';
            echo 'Aantal artikelen per product:' . $productaantal;
        } ?>
            </div>
        </div>
    </form>
</div>

    <div class="col-sm-2">
    <form class="form-horizontal col-sm-12" id="form_members" role="form" action="showbestelling.php?bestellingid=<?php echo $bestellingid?>" enctype="multipart/form-data" method="POST">
        <select name="status" class="col-sm-12">
            <?php
            $STH = $DBH->prepare('SELECT * FROM tblstatus ORDER BY statusid ASC');

            //vraag uitvoeren
            $STH->execute();
            $STH->setFetchMode(PDO::FETCH_OBJ);

            //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
            while($row = $STH->fetch()){
                $dbstatusid = $row->statusid;
                $statusnaam = $row->statusnaam;

                if($dbstatusid == $statusid ){
                    $selected = 'selected';
                } else {
                    $selected = '';
                }
                echo '<option value="' . $dbstatusid . '" ' . $selected .'>' . $statusnaam.'</option>';
            }
            ?>
        </select>
        <button type="submit" class="btn btn-block btn-primary col-sm-3" name="submit" id="submit">Status updaten</button>
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
