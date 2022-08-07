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
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="index.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
            <li class="active">Producten</li>
        </ol>
    </div><!--/.row-->

    <div class="input-group">
        <form action="prodlijst.php" method="get">
            <div>
                <input type="text" name="pn" placeholder="Product zoeken" class="form-control"/>

            </div>
        </form>
    </div>

    <table class="table">
        <thead>
        <tr>
            <th>Productnaam</th>
            <th>Wijnstreek</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        <?php
        
        
        if(isset($_GET['pn'])){
            //werd er een product opgezocht? --> alleen die producten tonen
            $search = '%' . $_GET['pn'] . '%';
            $STH = $DBH->prepare('SELECT * FROM tblproducten INNER JOIN tblregio ON tblproducten.regioid = tblregio.regioid WHERE tblproducten.prodnaam LIKE :search ORDER BY tblregio.regionaam, tblproducten.prodnaam ASC ');
            $STH->bindParam(':search',$search);

            //vraag uitvoeren
            $STH->execute();

            //tellen hoeveel records er voldoen aan de vraag
            $aantal = $STH -> rowCount();

            if($aantal==0){
                echo '<tr><td>Er werden geen producten teruggevonden. Klik <a href="prodlijst.php">hier</a> om alle producten te tonen.<td></tr>';
            } else {
                //methode van uitvoer
                $STH->setFetchMode(PDO::FETCH_OBJ);

                //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                while($row = $STH->fetch()){

                    $productid         = $row->productid;
                    $productnaam       = $row->prodnaam;
                    $regioid           = $row->regioid;
                    $regionaam         = $row->regionaam;
                    $promo             = $row->promotie;

                    //als hij in promotie staat --> afbeelding tonen
                    if ($promo == "0"){
                        $promotext = "";
                    } else {
                        $promotext = '<img src="../images/admin/promo.png" height="20px">';
                    }

                    echo '<tr><td><a href=showprod.php?productid='.$productid.'>' . $productnaam . '</a></td>';
                    echo '<td>' . $regionaam . '</td>';
                    echo '<td><a href="wijzigproduct.php?productid='. $productid .'"><img src="../images/admin/update.png" height="20"></a><a href="verwijderproduct.php?productid='. $productid .'"><img src="../images/admin/delete.png" height="20"></a></td>';
                    echo '<td>' . $promotext . '</td></tr>';
                }
            }
            
            
        } else {
            
            
            //geen searchbar input? --> alles weergeven
            $STH = $DBH->prepare('SELECT * FROM tblproducten INNER JOIN tblregio ON tblproducten.regioid = tblregio.regioid ORDER BY tblregio.regionaam, tblproducten.prodnaam ASC ');

            //vraag uitvoeren
            $STH->execute();

            //tellen hoeveel records er voldoen aan de vraag
            $aantal = $STH -> rowCount();

            if($aantal==0){
                echo '<tr><td>Er werden geen producten teruggevonden<td></tr>';
            } else {
                //methode van uitvoer
                $STH->setFetchMode(PDO::FETCH_OBJ);

                //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                while($row = $STH->fetch()){

                    $productid         = $row->productid;
                    $productnaam       = $row->prodnaam;
                    $regioid           = $row->regioid;
                    $regionaam         = $row->regionaam;
                    $promo             = $row->promotie;

                    //als hij in promotie staat --> afbeelding tonen
                    if ($promo == "0"){
                        $promotext = "";
                    } else {
                        $promotext = '<img src="../images/admin/promo.png" height="20px">';
                    }

                    echo '<tr><td><a href=showprod.php?productid='.$productid.'>' . $productnaam . '</a></td>';
                    echo '<td>' . $regionaam . '</td>';
                    echo '<td><a href="wijzigproduct.php?productid='. $productid .'"><img src="../images/admin/update.png" height="20"></a><a href="verwijderproduct.php?productid='. $productid .'"><img src="../images/admin/delete.png" height="20"></a></td>';
                    echo '<td>' . $promotext . '</td></tr>';
                }
            }
        }
        
        ?>
        </tbody>
    </table>
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
