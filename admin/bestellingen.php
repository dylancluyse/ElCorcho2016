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
                <li><a href="index.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
                <li class="active">Bestellingen</li>
            </ol>
        </div><!--/.row-->

    <div class="admin-edit">
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Naam</th>
                <th>Totaal</th>
                <th>Leveringswijze</th>
                <th>Leveringsadres</th>
                <th>Betaalwijze</th>
                <th>Datum v bestelling</th>
                <th>Status</th>

            </tr>
            </thead>
            <tbody>

            <?php
            $STH = $DBH->prepare('SELECT * FROM tblbestellingen ORDER BY datumtijd DESC');

            //vraag uitvoeren
            $STH->execute();

            //tellen hoeveel records er voldoen aan de vraag
            $aantal = $STH->rowCount();

            if ($aantal == 0) {
                echo '<tr><td></td><td>' . "Er werden geen bestellingen teruggevonden" . '</td>';

            } else {
                //methode van uitvoer
                $STH->setFetchMode(PDO::FETCH_OBJ);

                //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                while ($row = $STH->fetch()) {
                    $bestellingid = $row->bestellingid;
                    $klantid = $row->klantid;
                    $leveringsid = $row->leveringsid;
                    $betaalid = $row->betaalid;
                    $datumtijd = $row->datumtijd;
                    $statusid = $row->statusid;
                    $adres = $row->adres;

                    $STHklant = $DBH->prepare('SELECT * FROM tblklanten WHERE klantid = :klantid;');
                    $STHklant->bindParam(':klantid', $klantid);
                    $STHklant->execute();
                    $STHklant->setFetchMode(PDO::FETCH_OBJ);

                    //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                    while ($row = $STHklant->fetch()) {
                        $klantvnaam = $row->klantvnaam;
                        $klantfnaam = $row->klantfnaam;
                    }

                    /////////////////////////////////////////////SELECT VOOR LEVERING///////////////////////////////////////////////

                    $STHlevering = $DBH->prepare('SELECT * FROM tblleveringsmogelijkheden WHERE leveringsid = :leveringsid');

                    $STHlevering->bindParam(':leveringsid', $leveringsid);

                    $STHlevering->execute();

                    $STHlevering->setFetchMode(PDO::FETCH_OBJ);

                    //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                    while ($row = $STHlevering->fetch()) {
                        $leveringsnaam = $row->leveringswnaam;
                        $leveringsprijs = $row->leveringsprijs;
                    }

                    /////////////////////////////////////////////SELECT VOOR BETALING///////////////////////////////////////////////

                    $STHbetaling = $DBH->prepare('SELECT * FROM tblbetaalwijze WHERE betaalid = :betalingsid');
                    $STHbetaling->bindParam(':betalingsid', $betaalid);
                    $STHbetaling->execute();
                    $STHbetaling->setFetchMode(PDO::FETCH_OBJ);

                    //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                    while ($row = $STHbetaling->fetch()) {
                        $betaalwnaam = $row->betaalwnaam;
                    }

                    /////////////////////////////////////////////SELECT VOOR STATUS/////////////////////////////////////////////////

                    $STHstatus = $DBH->prepare('SELECT * FROM tblstatus WHERE statusid = :statusid');
                    $STHstatus->bindParam(':statusid', $statusid);
                    $STHstatus->execute();
                    $STHstatus->setFetchMode(PDO::FETCH_OBJ);

                    //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                    while ($row = $STHstatus->fetch()) {
                        $statusnaam = $row->statusnaam;
                    }




                    //bestelling tabel tonen
                    echo '<tr><td><a href="showbestelling.php?bestellingid='. $bestellingid .'">'. $bestellingid .'</a></td>';
                    echo '<td>' . $klantvnaam . ' ' . $klantfnaam . '<br></td>';


                    $STHbest = $DBH->prepare('SELECT * FROM tblprodperbestelling WHERE bestellingid = :bestellingid;');

                    $STHbest->bindParam(':bestellingid', $bestellingid);

                    $STHbest->execute();

                    $STHbest->setFetchMode(PDO::FETCH_OBJ);

                    $subtotaal = 0;



                    //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                    while ($row = $STHbest->fetch()) {
                        $productid = $row->productid;
                        $prijs = $row->prijs;
                        $aantal = $row->aantal;

                        $STHprod = $DBH->prepare('SELECT * FROM tblproducten WHERE productid = :productid;');

                        $STHprod->bindParam(':productid', $productid);

                        $STHprod->execute();

                        $STHprod->setFetchMode(PDO::FETCH_OBJ);

                        //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                        while ($row = $STHprod->fetch()) {
                            $productnaam = $row->prodnaam;

                            $inclbtw = $prijs * 1.21;
                            $totaalinclbtw = ($aantal * $inclbtw) + $leveringsprijs;

                        }
                        $subtotaal = $subtotaal + $totaalinclbtw;
                    }

                    echo '<td> €' . round($subtotaal, 2) . '</td>';

                    echo '<td>' . $leveringsnaam . ' - €' . $leveringsprijs . '<br></td>';
                    echo '<td>' . $adres . '<br></td>';
                    echo '<td>' . $betaalwnaam . '<br></td>';
                    echo '<td>' . $datumtijd . '<br></td>';
                    echo '<td>' . $statusnaam . '<br></td></tr>';
                }
                echo '</tbody></table>';
            }
            ?>
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
