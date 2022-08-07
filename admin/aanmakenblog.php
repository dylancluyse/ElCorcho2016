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
    include_once('../script/script-resize.php')
    ?>
</head>

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

<?php
if(isset($_POST['submit'])){

    $blognaam = $_POST['blognaam'];
    $blogomsch = $_POST['blogomschrijving'];
    $blogkortomsch = $_POST['blogkorteomschrijving'];
    $blogdatum = date("Y-m-d H:i:s");

    //via named placeholders
    $STH = $DBH->prepare('INSERT INTO tblblog(blogtitel, blogtekst, blogkorteomschrijving, blogdatum)VALUE(:blogtitel, :blogtekst, :blogkorteomschrijving, :blogdatum)');

    //named placeholders aanmaken
    $STH->bindParam(':blogtitel', $blognaam);
    $STH->bindParam(':blogtekst', $blogomsch);
    $STH->bindParam(':blogkorteomschrijving', $blogkortomsch);
    $STH->bindParam(':blogdatum', $blogdatum);

    //query/vraag uitvoeren
    $STH->execute();

    //foto invoegen voor blog
    //lastid opvragen
    $blogid = $DBH->lastInsertId();

    if (count($_FILES['file']['name']) > 0) {
        for ($i = 0; $i < count($_FILES['file']['name']); $i++) {

            $tmpFilePath = $_FILES['file']['tmp_name'][$i];

            if ($tmpFilePath != "") {
                $shortname = $_FILES['file']['name'][$i];

                $filePath = "doc/"  . $_FILES['file']['name'][$i];

                $ext = pathinfo($shortname, PATHINFO_EXTENSION);

                $allowed = array('jpg', 'png');

                $filename = $_FILES['file']['name'];

                if (!in_array($ext, $allowed)) {
                    echo 'Uw bestanden werden niet toegevoegd. Je kan alleen maar JPG EN/OF PNG-bestanden toevoegen.';
                } else {
                    //file uploaden
                    if (move_uploaded_file($tmpFilePath, $filePath)) {
                        $files[] = $shortname;

                        //information needed for the function
                        $src_path = 'doc/'; // Path of the source image that will be resized.
                        $src_img = $shortname; // Source image that will be resized.
                        $dst_img = $shortname; // This name will be given to the resized image.
                        $dst_path = 'doc/'; // In this path the resized image will be saved
                        $dst_w = 400; // The width of the resized image
                        $dst_h = 400; // The height of the resized image
                        $dst_quality = 50; // Quality of the resized image (best quality = 100)

                        resizeImage($src_img, $dst_img, $src_path, $dst_path, $dst_w, $dst_h, $dst_quality);
                    }
                }
            }
        }
    } else {
        $_SESSION['foutmelding'] = "Foutmelding";
    }

    if (!in_array($ext, $allowed)) {
    } else {
        if (is_array($files)) {
            foreach ($files as $file) {

                $STHfoto = $DBH->prepare('INSERT INTO tblblogfoto(blogid, fotonaam)VALUE(:blogid, :fotonaam)');

                $STHfoto->bindParam(':blogid', $blogid);
                $STHfoto->bindParam(':fotonaam', $file);

                //query/vraag uitvoeren
                $STHfoto->execute();

            }
        }
    }

}

?>

<body>
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="container-fluid"><br>
        
        <!--form voor het aanmaken van nieuwe smaken-->
        <form class="form-horizontal" id="form_members" role="form" action="" enctype="multipart/form-data" method="POST">
            <legend>Naam</legend>
            <div class="form-group">
                <label for="firstname" class="col-sm-2">Blogtitel</label>
                <div class="col-sm-4 required">
                    <input type="text" class="form-control" name="blognaam" id="prodnaam" required autofocus maxlength="100">
                </div>
            </div>
    </div>

        <legend>Informatie</legend>
        <div class="form-group">
            <label for="korteomschrijving" class="col-sm-2">Korte omschrijving</label>
            <div class="col-sm-10">
                <textarea type="text" class="form-control" name="blogkorteomschrijving" id="omschrijving" maxlength="500"></textarea>
            </div>
        </div>

        <div class="form-group">
        <label for="omschrijving" class="col-sm-2">Omschrijving</label>
            <div class="col-sm-10">
                <textarea type="text" class="form-control" name="blogomschrijving" id="omschrijving" maxlength="2000"></textarea>
            </div>
        </div>

        <label for="file">Voeg afbeeldingen toe</label>
        <input type="file" id="file" name="file[]" multiple="multiple">


        <div class="form-group">
            <div class="col-sm-3 text-right" >
                <button type="submit" class="btn btn-block btn-primary" name="submit" id="submit">Blog toevoegen</button>
            </div>
        </div>
    </form>

<div>
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Titel</th>
                <th>Datum van plaatsing</th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            <?php

            $STH = $DBH->prepare('SELECT * FROM tblblog');
            $STH->execute();
            $STH->setFetchMode(PDO::FETCH_OBJ);

            while($row = $STH->fetch()){
                $blogid = $row->blogid;
                $blogtitel = $row->blogtitel;
                $blogdatum = $row->blogdatum;

                    echo '<tr><td>' . $blogid . '</td><td>'.$blogtitel.'</td><td>'.$blogdatum.'</td>';
                    echo '<td><a href="wijzigblog.php?blogID='. $blogid .'"><img src="../images/admin/update.png" width="20"></a><a href="verwijderblog.php?blogID='. $blogid .'"> <img src="../images/admin/delete.png" width="20"></a></td></tr>';
            }
            ?>

            </tbody>
        </table>
</div>
        <?php


        ?>
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
