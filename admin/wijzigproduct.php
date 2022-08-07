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

//geen productid meegegeven?
if(!isset($_GET['productid'])){
    header('location:prodlijst.php');
}

if(isset($_GET['delfoto'])){
    $fotoid = $_GET['delfoto'];
    $productid = $_GET['productid'];

    //fotonaam selecteren
    $STH = $DBH->prepare('SELECT fotonaam FROM tblprodfoto WHERE productid = :productid AND fotoid = :fotoid');
    $STH->bindParam(':fotoid',$fotoid);
    $STH->bindParam(':productid', $productid);
    $STH->execute();
    $STH->setFetchMode(PDO::FETCH_OBJ);
    $row = $STH->fetch();
    $fotonaam = $row->fotonaam;


    //foto verwijderen uit database
    $STH = $DBH->prepare('DELETE FROM tblprodfoto WHERE productid = :productid AND fotoid = :fotoid');
    $STH->bindParam(':fotoid',$fotoid);
    $STH->bindParam(':productid', $productid);
    $STH->execute();
    
    
    //foto verwijderen uit mapje
    unlink('doc/'.$fotonaam);
}


//mainfoto veranderen?
if(isset($_GET['action'])) {

    $main = $_GET['action'];
    $fotoid = $_GET['mainfoto'];

    $STH = $DBH->prepare('UPDATE tblprodfoto SET main = :main WHERE fotoid = :fotoid');
    $STH->bindParam(':main', $main);
    $STH->bindParam(':fotoid', $fotoid);
    $STH->execute();

    header('location:wijzigproduct.php?productid='.$_GET['productid']);
}

if(isset($_POST['submit'])){

    $productid = $_GET['productid'];
    $STHfoto = $DBH->prepare('SELECT * FROM tblprodfoto WHERE productid = :productid');

    //tel aantal bestanden
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
                        $dst_w = 400;          // The width of the resized image
                        $dst_h = 400;          // The height of the resized image
                        $dst_quality = 50;     // Quality of the resized image (best quality = 100)

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

                $STH = $DBH->prepare('SELECT * FROM tblprodfoto WHERE productid = :productid');
                $STH->bindParam(':productid',$productid);
                $STH->execute();

                $amount = $STH->rowCount();

                if($amount>=3){
                    
                } else {
                    $STHfoto = $DBH->prepare('INSERT INTO tblprodfoto(productid, fotonaam)VALUE(:productid, :fotonaam)');

                    $STHfoto->bindParam(':productid', $productid);
                    $STHfoto->bindParam(':fotonaam', $file);

                    //query/vraag uitvoeren
                    $STHfoto->execute();
                }


            }
        }
    }

    $productnaam       = $_POST['prodnaam'];
    $productcat        = $_POST['prodcat'];
    $productproducent  = $_POST['prodproducent'];
    $productregio      = $_POST['prodregio'];
    $productsmaak      = $_POST['prodsmaak'];
    $productdruif      = $_POST['proddruif'];
    $productomschrijving  = $_POST['prodomschrijving'];
    $productstock      = $_POST['prodstock'];
    $productprijs      = $_POST['prodprijs'];
    $productpromo      = $_POST['prodpromo'];
    $jaar              = $_POST['jaar'];
    $inhoud            = $_POST['inhoud'];

    //via named placeholders
    $STHadd = $DBH->prepare
    ('UPDATE tblproducten
          SET prodnaam = :prodnaam, regioid = :regioid, categorieid = :categorieid, prijs = :prijs, voorraad = :voorraad, smaakid = :smaakid, producentid = :producentid, druifid = :druifid, omschrijving = :omschrijving, promotie = :productpromo, jaar = :jaar, inhoud = :inhoud
          WHERE productid = :prodid');

    //named placeholders aanmaken
    $STHadd->bindParam(':prodid', $productid);
    $STHadd->bindParam(':prodnaam', $productnaam);
    $STHadd->bindParam(':regioid', $productregio);
    $STHadd->bindParam(':categorieid', $productcat);
    $STHadd->bindParam(':prijs', $productprijs);
    $STHadd->bindParam(':voorraad', $productstock);
    $STHadd->bindParam(':smaakid', $productsmaak);
    $STHadd->bindParam(':producentid', $productproducent);
    $STHadd->bindParam(':druifid', $productdruif);
    $STHadd->bindParam(':omschrijving', $productomschrijving);
    $STHadd->bindParam(':productpromo', $productpromo);
    $STHadd->bindParam(':jaar', $jaar);
    $STHadd->bindParam(':inhoud', $inhoud);

    //query/vraag uitvoeren
    $STHadd->execute();

    header('location:prodlijst.php');
}

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
        $productid    = $row->productid;
        $prodnaam     = $row->prodnaam;
        $prijs        = $row->prijs;
        $promotie     = $row->promotie;
        $voorraad     = $row->voorraad;
        $omschrijving = $row->omschrijving;
        $prodsmaak    = $row->smaakid;
        $prodregio    = $row->regioid;
        $proddruif    = $row->druifid;
        $prodproducent = $row->producentid;
        $prodcat       = $row->categorieid;
        $prodjaar = $row->jaar;
        $prodinhoud = $row->inhoud;
    }


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
    <!--form voor het aanmaken van nieuwe smaken-->
    <form class="form-horizontal" id="form_members" role="form" action="" enctype="multipart/form-data" method="POST">
        <legend>Naam</legend>
        <div class="form-group">
            <label for="firstname" class="col-sm-2">Naam v/h product</label>
            <div class="col-sm-6 required">
                <input type="text" class="form-control" name="prodnaam" id="prodnaam" placeholder="" required autofocus maxlength="100" value="<?php echo $prodnaam; ?>">
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

                        if($catid == $prodcat ){
                            $selected = 'selected';
                        } else {
                            $selected = '';
                        }
                        echo '<option value="' . $catid . '" ' . $selected .'>' . $catnaam.'</option>';
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

                        if($producentid == $prodproducent ){
                            $selected = 'selected';
                        } else {
                            $selected = '';
                        }
                        echo '<option value="' . $producentid . '" ' . $selected .'>' . $producentnaam.'</option>';
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

                        if($regioid == $prodregio ){
                            $selected = 'selected';
                        } else {
                            $selected = '';
                        }
                        echo '<option value="' . $regioid . '" ' . $selected .'>' . $regionaam.'</option>';
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

                        if($smaakid == $prodsmaak ){
                            $selected = 'selected';
                        } else {
                            $selected = '';
                        }

                        echo '<option value="' . $smaakid . '" ' . $selected .'>' . $smaaknaam.'</option>';
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

                        if($druifid == $proddruif ){
                            $selected = 'selected';
                        } else {
                            $selected = '';
                        }

                        echo '<option value="' . $druifid . '" ' . $selected .'>' . $druifnaam .'</option>';
                    }
                    ?>
                </select>
            </div>
        </div>


        <div class="form-group">
            <label for="jaar" class="col-sm-2">Jaar</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="jaar" id="jaar" maxlength="4" value="<?php echo $prodjaar?>">
            </div>
            <label for="inhoud" class="col-sm-2">Inhoud (in centiliter)</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="inhoud" id="inhoud" maxlength="10" value="<?php echo $prodinhoud ?>">
            </div>
        </div>


        <legend>Omschrijving</legend>
        <div class="form-group">
            <label for="omschrijving" class="col-sm-2">Omschrijving</label>
            <div class="col-sm-10">
                <textarea type="text" class="form-control" name="prodomschrijving" id="omschrijving" rows="10" maxlength="750"><?php echo $omschrijving; ?></textarea>
            </div>
        </div>
        <legend>Voorraad</legend>
        <div class="form-group">
            <label for="stock" class="col-sm-2">Stock</label>
            <div class="col-sm-2 required">
                <input type="text" class="form-control" name="prodstock" id="stock" placeholder="vb: 25 (stuks)" required autofocus value="<?php echo $voorraad; ?>">
            </div>
        </div>

        <legend>Prijs</legend>
        <div class="form-group">
            <label for="price" class="col-sm-2">Prijs</label>
            <div class="col-sm-2 required">
                <input type="text" class="form-control" name="prodprijs" id="price" placeholder="" required autofocus value="<?php echo $prijs; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="promotion" class="col-sm-2">Promotie</label>
            <div class="col-sm-2 required">
                <input type="text" class="form-control" name="prodpromo" id="promotion" placeholder="" required autofocus value="<?php echo $promotie; ?>">
            </div>
        </div>

        <label for="file">Voeg afbeeldingen toe</label>
        <input type="file" id="file" name="file[]" multiple="multiple">

        <div class="form-group">
            <div class="col-sm-3 text-right" >
                <button type="submit" class="btn btn-block btn-primary" name="submit" id="submit">Product wijzigen</button>
            </div>
        </div>
        <!--form sluiten-->
        <legend>Foto's</legend>

        <?php

        $STH = $DBH->prepare('SELECT * FROM tblprodfoto WHERE productid = :productid');
        $STH->bindParam(':productid', $productid);

        //vraag uitvoeren
        $STH->execute();
        $aantal = $STH->rowCount();

        if($aantal==0){
            echo " Er zijn geen foto's gekoppeld aan dit product.";
        } else {
            //melding geven hoeveel foto's je maximaal mag gebruiken per product.
            $aantal = 3 - $aantal;
            echo "Je kan nog maximaal " . $aantal . " foto('s) toevoegen aan dit product." . '<br><br>';
            echo "Klik op de foto die je wilt verwijderen.";

            //methode van uitvoer
            $STH->setFetchMode(PDO::FETCH_OBJ);
            while ($row = $STH->fetch()) {
                $fotoid   = $row->fotoid;
                $filename = $row->fotonaam;
                ?>

                <div class="media">
                    <?php
                    echo '<a href="wijzigproduct.php?delfoto=' . $fotoid . '&productid='.$productid.'"><img class="media_image" src="doc/' . $filename . '" width="200"></a><br><br>';
                    ?>
                </div>

                <?php
            }
        }
        ?>
        <br>
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
