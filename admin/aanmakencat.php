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
//connectie met database invoegen
include_once('../include/connection.php');
include_once('../include/accesscontrol.php');

if(isset($_POST['submit'])){

    $categorienaam = $_POST['catnaam'];

    //via named placeholders
    $STHadd = $DBH->prepare('INSERT INTO tblcategorie(categorienaam)VALUE(:categorienaam)');

    //named placeholders aanmaken
    $STHadd->bindParam(':categorienaam', $categorienaam);

    //query/vraag uitvoeren
    $STHadd->execute();
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
        <li><a href="klantlijst.php"><svg class="glyph stroked app-window"><use xlink:href="#stroked-app-window"></use></svg> Klanten</a></li>
        <li><a href="wijzigpwd.php"><svg class="glyph stroked app-window"><use xlink:href="#stroked-app-window"></use></svg> Wachtwoord veranderen</a></li>
    </ul>
</div><!--/.sidebar-->



<body>
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="container-fluid">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="index.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
                <li class="active">Categorie</li>
            </ol>
        </div><!--/.row--><br><br>

        <!--form voor het aanmaken van nieuwe smaken-->
        <form class="form-horizontal" id="form_members" role="form" action="" method="POST">
            <div class="form-group">
                <label for="lastname" class="col-sm-8">Naam</label>
                <div class="col-sm-4">
                    <input type="text" input class="form-control focusedInput" name="catnaam" id="name" required>
                </div>

                </br></br></br></br>

                <div class="form-group">
                    <div class="col-sm-3 text-right" >
                        <button type="submit" class="btn btn-block btn-primary" name="submit" id="submit">Categorie toevoegen</button>
                    </div>
                </div>
            </div>
        </form>
        <!--form sluiten-->
    </div>

    <table class="table">
        <thead>
        <tr>
            <th>Categorie</th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        <?php
        $STH = $DBH->prepare('SELECT * FROM tblcategorie ORDER BY categorienaam ASC');

        //vraag uitvoeren
        $STH->execute();

        //tellen hoeveel records er voldoen aan de vraag
        $STH->setFetchMode(PDO::FETCH_OBJ);

        $aantal = $STH -> rowCount();

        if($aantal==0){
            echo "Er zijn geen categoriën gevonden";
        } else {
            //methode van uitvoer
            $STH->setFetchMode(PDO::FETCH_OBJ);

            //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
            while($row = $STH->fetch()){

                $catnaam           = $row->categorienaam;
                $catid             = $row->categorieid;

                echo '<tr><td>' . $catnaam . '</td>';
                echo '<td><a href="wijzigcat.php?catid='. $catid .'"><img src="../images/admin/update.png" height="20" width="20"></a><a href="verwijdercat.php?catid='. $catid .'"><img src="../images/admin/delete.png" height="20" width="20"></a></td></tr>';

            }
        }
        ?>
        </tbody>
    </table>

    <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
            </div>
        </div> 
</div>

    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/chart.min.js"></script>
    <script src="js/chart-data.js"></script>
    <script src="js/easypiechart.js"></script>
    <script src="js/easypiechart-data.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script src="js/bootstrap-table.js"></script>
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