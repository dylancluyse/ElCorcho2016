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
    include_once('../script/script-resize.php');
    ?>
</head>

<?php

//op knop geklikt?
if(isset($_POST['submit'])){
    $prodnaam = $_POST['prodnaam'];
    $prodcat  = $_POST['prodcat'];
    $prodproducent  = $_POST['prodproducent'];
    $prodregio  = $_POST['prodregio'];
    $prodsmaak  = $_POST['prodsmaak'];
    $proddruif  = $_POST['proddruif'];
    $prodomschrijving  = $_POST['prodomschrijving'];
    $prodstock  = $_POST['prodstock'];
    $prodprijs  = $_POST['prodprijs'];
    $jaar = $_POST['jaar'];
    $inhoud = $_POST['inhoud'];

    //via named placeholders
    $STHadd = $DBH->prepare('INSERT INTO tblproducten(prodnaam, regioid, categorieid, prijs, voorraad, smaakid, producentid, druifid, omschrijving, jaar, inhoud)VALUE(:prodnaam, :regioid, :categorieid, :prijs, :voorraad, :smaakid, :producentid, :druifid, :omschrijving, :jaar, :inhoud)');

    //named placeholders aanmaken
    $STHadd->bindParam(':prodnaam', $prodnaam);
    $STHadd->bindParam(':regioid', $prodregio);
    $STHadd->bindParam(':categorieid', $prodcat);
    $STHadd->bindParam(':prijs', $prodprijs);
    $STHadd->bindParam(':voorraad', $prodstock);
    $STHadd->bindParam(':smaakid', $prodsmaak);
    $STHadd->bindParam(':producentid', $prodproducent);
    $STHadd->bindParam(':druifid', $proddruif);
    $STHadd->bindParam(':omschrijving', $prodomschrijving);
    $STHadd->bindParam(':jaar', $jaar);
    $STHadd->bindParam(':inhoud', $inhoud);


    //query/vraag uitvoeren
    $STHadd->execute();

    //lastid opvragen
    $productid = $DBH->lastInsertId();

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
                    $error = 1;
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

                $STHfoto = $DBH->prepare('INSERT INTO tblprodfoto(productid, fotonaam)VALUE(:productid, :fotonaam)');

                $STHfoto->bindParam(':productid', $productid);
                $STHfoto->bindParam(':fotonaam', $file);

                //query/vraag uitvoeren
                $STHfoto->execute();
            }
        }
    }
    header('location:prodlijst.php');
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



<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="index.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
            <li class="active">Icons</li>
        </ol>
    </div><!--/.row-->
    <div class="container-fluid">
    <form class="form-horizontal" id="form_members" role="form" action="" enctype="multipart/form-data" method="POST">
        <legend>Naam</legend>
        <div class="form-group">
            <label for="firstname" class="col-sm-2">Naam v/h product</label>
            <div class="col-sm-4 required">
                <input type="text" class="form-control" name="prodnaam" id="prodnaam" required autofocus maxlength="100">
            </div>
        </div>

        <legend>Details</legend>
        <div class="form-group">
            <label for="cat" class="col-sm-2">Categorie</label>
            <div class="col-sm-4">
                <select name="prodcat">
                    <?php
                    $STHcat = $DBH->prepare('SELECT * FROM tblcategorie ORDER BY categorienaam ASC');

                    //vraag uitvoeren
                    $STHcat->execute();

                    $STHcat->setFetchMode(PDO::FETCH_OBJ);

                    //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                    while($row = $STHcat->fetch()){

                        $catid             = $row->categorieid;
                        $catnaam           = $row->categorienaam;

                        echo '<option value="'.$catid.'">'.$catnaam.'</option>';
                    }
                    ?>
                </select>
            </div>

            <label for="producent" class="col-sm-2">Producent</label>
            <div class="col-sm-4">
                <select name="prodproducent">
                    <?php
                    $STHproducent = $DBH->prepare('SELECT * FROM tblproducent ORDER BY producentnaam ASC');
                    //vraag uitvoeren
                    $STHproducent->execute();
                    $STHproducent->setFetchMode(PDO::FETCH_OBJ);

                    //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                    while($row = $STHproducent->fetch()){
                        $producentid           = $row->producentid;
                        $producentnaam           = $row->producentnaam;
                        echo '<option value="'.$producentid.'">'.$producentnaam.'</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="regio" class="col-sm-2">Regio</label>
            <div class="col-sm-4">
                <select name="prodregio">
                    <?php
                    $STHregio = $DBH->prepare('SELECT * FROM tblregio ORDER BY regionaam ASC');

                    $STHregio->bindParam(':landid', $landid);

                    //vraag uitvoeren
                    $STHregio->execute();
                    $STHregio->setFetchMode(PDO::FETCH_OBJ);

                    //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                    while($row = $STHregio->fetch()){
                        $regioid           = $row->regioid;
                        $regionaam           = $row->regionaam;
                        echo '<option value="'.$regioid.'">' .$regionaam.'</option>';
                    }
                    ?>
                </select>
            </div>

            <label for="smaak" class="col-sm-2">Smaakprofiel</label>
            <div class="col-sm-4">
                <select name="prodsmaak">
                    <?php
                    $STHsmaak = $DBH->prepare('SELECT * FROM tblsmaakprofiel ORDER BY smaaknaam ASC');
                    //vraag uitvoeren
                    $STHsmaak->execute();
                    $STHsmaak->setFetchMode(PDO::FETCH_OBJ);

                    //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                    while($row = $STHsmaak->fetch()){
                        $smaakid             = $row->smaakid;
                        $smaaknaam           = $row->smaaknaam;
                        echo '<option value="'.$smaakid.'">'.$smaaknaam.'</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="druif" class="col-sm-2">Druifsoort</label>
            <div class="col-sm-4">
                <select name="proddruif">
                    <?php
                    $STHdruiven = $DBH->prepare('SELECT * FROM tbldruiven ORDER BY druifnaam ASC');
                    //vraag uitvoeren
                    $STHdruiven->execute();
                    $STHdruiven->setFetchMode(PDO::FETCH_OBJ);

                    //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
                    while($row = $STHdruiven->fetch()){
                        $druifid           = $row->druifid;
                        $druifnaam         = $row->druifnaam;
                        echo '<option value="'.$druifid.'">'.$druifnaam.'</option>';
                    }
                    ?>
                </select>
            </div>

        </div>

        <div class="form-group">
            <label for="jaar" class="col-sm-2">Jaar</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="jaar" id="jaar" maxlength="4">
            </div>
            <label for="inhoud" class="col-sm-2">Inhoud (in centiliter)</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="inhoud" id="inhoud" maxlength="10">
            </div>
        </div>

        </div>
        <legend>Omschrijving</legend>
        <div class="form-group">
            <label for="omschrijving" class="col-sm-2">Omschrijving</label>
            <div class="col-sm-10">
                <textarea type="text" class="form-control" name="prodomschrijving" id="omschrijving" maxlength="500"></textarea>
            </div>
        </div>
        <legend>Voorraad</legend>
        <div class="form-group">
            <label for="stock" class="col-sm-2">Stock</label>
            <div class="col-sm-2 required">
                <input type="text" class="form-control" name="prodstock" id="stock" required autofocus>
            </div>
        </div>

        <legend>Prijs</legend>
        <div class="form-group">
            <label for="price" class="col-sm-2">Prijs</label>
            <div class="col-sm-2 required">
                <input type="text" class="form-control" name="prodprijs" id="price" required autofocus>
            </div>
        </div>

        <legend></legend>
        <label for="file">Voeg afbeeldingen toe</label>
        <input type="file" id="file" name="file[]" multiple="multiple">


        <div class="form-group">
            <div class="col-sm-3 text-right" >
                <button type="submit" class="btn btn-block btn-primary" name="submit" id="submit">Product toevoegen</button>
            </div>
        </div>
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
