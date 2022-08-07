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
//geen blogid meegegeven?
if(!isset($_GET['blogID'])){
    header('location:aanmakenblog.php');
}

if(isset($_GET['delfoto'])){
    $fotoid = $_GET['delfoto'];
    $blogid = $_GET['blogID'];

    //fotonaam selecteren
    $STH = $DBH->prepare('SELECT fotonaam FROM tblblogfoto WHERE blogid = :blogid AND fotoid = :fotoid');
    $STH->bindParam(':fotoid',$fotoid);
    $STH->bindParam(':blogid', $blogid);
    $STH->execute();
    $STH->setFetchMode(PDO::FETCH_OBJ);


    $row = $STH->fetch();
    $fotonaam = $row->fotonaam;


    //foto verwijderen uit database
    $STH = $DBH->prepare('DELETE FROM tblblogfoto WHERE blogid = :blogid AND fotoid = :fotoid');
    $STH->bindParam(':fotoid',$fotoid);
    $STH->bindParam(':blogid', $blogid);
    $STH->execute();


    //foto verwijderen uit mapje
    unlink('doc/'.$fotonaam);
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

                $STH = $DBH->prepare('SELECT * FROM tblblogfoto WHERE blogid = :blogid');
                $STH->bindParam(':blogid',$blogid);
                $STH->execute();

                $amount = $STH->rowCount();

                if($amount>=3){

                } else {
                    $STHfoto = $DBH->prepare('INSERT INTO tblblogfoto(blogid, fotonaam)VALUE(:productid, :fotonaam)');

                    $STHfoto->bindParam(':blogid', $blogid);
                    $STHfoto->bindParam(':fotonaam', $file);

                    //query/vraag uitvoeren
                    $STHfoto->execute();
                }


            }
        }
    }

    $productid        = $_POST['productid'];
    $blogtekst        = $_POST['tekst'];
    $blogtitel        = $_POST['titel'];
    $blogkorteomschrijving = $_POST['kortetekst'];


    //via named placeholders
    $STHadd = $DBH->prepare('UPDATE tblblog
          SET blogtitel = :blogtitel, blogtekst = :blogtekst, blogkorteomschrijving = :blogkorteomschrijving           
          WHERE blogid = :blogid');

    //named placeholders aanmaken
    $STHadd->bindParam(':blogid',$_GET['blogID']);
    $STHadd->bindParam(':blogtitel', $blogtitel);
    $STHadd->bindParam(':blogtekst', $blogtekst);
    $STHadd->bindParam(':blogkorteomschrijving', $blogkorteomschrijving);

    //query/vraag uitvoeren
    $STHadd->execute();

    header('location:wijzigblog.php?blogid='.$blogid);
}





$blogid = $_GET['blogID'];

$STH = $DBH->prepare('SELECT * FROM tblblog WHERE blogid = :blogid');

$STH->bindParam(':blogid', $blogid);

//vraag uitvoeren
$STH->execute();

$aantal = $STH -> rowCount();

if($aantal==0){
} else {
    //methode van uitvoer
    $STH->setFetchMode(PDO::FETCH_OBJ);

    //zolang er gegevens in de database zitten die voldoen aan de vraag: weergeven
    while($row = $STH->fetch()){
        $titel    = $row->blogtitel;
        $tekst     = $row->blogtekst;
        $kortetekst        = $row->blogkorteomschrijving;
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
            <li class="active">Blog</li>
        </ol>
    </div><!--/.row-->



    <!--form voor het wijzigen van blog-->
    <form class="form-horizontal" id="form_members" role="form" action="wijzigblog.php?blogID=<?php echo $blogid;?>" method="POST">
        <legend>Inhoud</legend>
        <div class="form-group">
            <label for="firstname" class="col-sm-2">Titel</label>
            <div class="col-sm-6 required">
                <input type="text" class="form-control" name="titel" placeholder="" required maxlength="50" value="<?php echo $titel; ?>">
            </div>
        </div>


        <div class="form-group">
            <label for="jaar" class="col-sm-2">Tekst</label>
            <div class="col-sm-8">
                <textarea type="text" class="form-control" name="tekst" id="jaar" rows="5"><?php echo $tekst?></textarea>
            </div>
        </div>


        <div class="form-group">
        <label for="inhoud" class="col-sm-2">Korte omschrijving</label>
            <div class="col-sm-8">
                <textarea type="text" class="form-control" name="kortetekst" rows="5" id="inhoud"><?php echo $kortetekst ?></textarea>
            </div>
        </div>
        

        <label for="file">Voeg afbeeldingen toe</label>
        <input type="file" id="file" name="file[]" multiple="multiple">

        <div class="form-group">
            <div class="col-sm-3 text-right" >
                <button type="submit" class="btn btn-block btn-primary" name="submit" id="submit">Blog wijzigen</button>
            </div>
        </div>
        </form>
        <!--form sluiten-->
        <legend>Foto</legend>

        <?php

        $STH = $DBH->prepare('SELECT * FROM tblblogfoto WHERE blogid = :blogid');
        $STH->bindParam(':blogid', $blogid);

        //vraag uitvoeren
        $STH->execute();
        $aantal = $STH->rowCount();

        if($aantal==0){
            echo " Er is geen foto gekoppeld aan dit blogartikel.";
        } else {
            //methode van uitvoer
            $STH->setFetchMode(PDO::FETCH_OBJ);
            while ($row = $STH->fetch()) {
                $fotoid   = $row->fotoid;
                $filename = $row->fotonaam;
                ?>

                <div class="media">
                    <?php
                    echo '<img class="media_image" src="doc/' . $filename . '" width="200">';
                    ?>

                    <div class="media_body">
                        <?php
                        echo '<a href="wijzigblog.php?delfoto=' . $fotoid . '&blogid='.$blogid.'">X</a><br><br>';
                        ?>
                    </div>
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
</body>

</html>
