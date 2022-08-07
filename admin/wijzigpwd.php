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

if(isset($_POST['submit'])){

    $oudwachtwoord = $_POST['oldpwd'];
    $nieuwwachtwoord = $_POST['newpwd'];
    $adminlogin = "admin";



    $STH = $DBH->prepare('SELECT * FROM tbladmin WHERE adminlogin = :admin');
    $STH->bindParam(':admin',$adminlogin);
    $STH->execute();
    $STH->setFetchMode(PDO::FETCH_OBJ);
    while($row = $STH->fetch()){
        $oudwachtwoord_db = $row->adminpwd;
    }



    //verkeerd oud wachtwoord ingegeven? foutmelding + doorsturen
    if ($oudwachtwoord_db != $oudwachtwoord){
        $_SESSION['error'] = "U heeft een verkeerd wachtwoord ingegeven.";
        header('location:wijzigpwd.php');
        exit();
    }


    //via named placeholders
    $STH = $DBH->prepare('UPDATE tbladmin SET adminpwd = :adminpwd WHERE adminlogin = :adminlogin');

    //named placeholders aanmaken
    $STH->bindParam(':adminlogin', $adminlogin);
    $STH->bindParam(':adminpwd', $nieuwwachtwoord);

    //query/vraag uitvoeren
    $STH->execute();
    header('location:login.php');
    exit();
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
        <li><a href="bestellingen.php"><svg class="glyph stroked app-window"><use xlink:href="#stroked-app-window"></use></svg> Bestellingen</a></li>
        <li><a href="klantlijst.php"><svg class="glyph stroked app-window"><use xlink:href="#stroked-app-window"></use></svg> Klanten</a></li>
        <li class="active"><a href="wijzigpwd.php"><svg class="glyph stroked app-window"><use xlink:href="#stroked-app-window"></use></svg> Wachtwoord veranderen</a></li>
    </ul>
</div><!--/.sidebar-->


<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="index.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
            <li class="active">Admin-password veranderen</li>
        </ol>
    </div><!--/.row-->


    <?php
    ///////////////////
    //Wachtwoord//
    ///////////////////


    $STH = $DBH->prepare('SELECT * FROM tbladmin WHERE adminlogin = :adminlogin');

    $adminlogin = "admin";

    $STH->bindParam(':adminlogin', $adminlogin);

    //vraag uitvoeren
    $STH->execute();

    //tellen hoeveel records er voldoen aan de vraag
    $aantal = $STH -> rowCount();

    if($aantal==0){
    } else {
        //methode van uitvoer
        $STH->setFetchMode(PDO::FETCH_OBJ);

        //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
        while($row = $STH->fetch()){
            $oldpwd = $row->adminpwd;
        }
    }

    ?>

    <!--form voor het aanmaken van nieuwe smaken-->
    <div class="admin-edit">
    <div class="container-fluid">
        <form class="form-horizontal" id="form_members" role="form" action="wijzigpwd.php" method="POST">

            <?php
            if(isset($_SESSION['error'])){
                echo $_SESSION['error'];
                unset($_SESSION['error']);
            }
            ?>
            <div class="form-group">
                <div class="col-sm-2 col-lg-4">
                    <input type="text" input class="form-control focusedInput" name="oldpwd" id="name" required value="" placeholder="oud wachtwoord">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 col-lg-4">
                    <input type="text" input class="form-control focusedInput" name="newpwd" id="name" required value="" placeholder="nieuw wachtwoord">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2">
                    <button type="submit" class="btn btn-block btn-primary" name="submit" id="submit">Wachtwoord wijzigen</button>
                </div>
            </div>
        </form>
    </div>
    </div>
    <!--form sluiten-->


</div>

<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/chart.min.js"></script>
<script src="js/chart-data.js"></script>
<script src="js/easypiechart.js"></script>
<script src="js/easypiechart-data.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/bootstrap-table.js"></script>
</body>
</html>