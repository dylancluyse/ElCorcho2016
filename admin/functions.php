<?php

// Author: Ruben Vandenbussche
// Website: http://www.RVandenbussche.nl
// Contact: info (at) RVandenbussche (dot) nl

//*******************************************
// Script: Image Resize v1.1
//It does:
//  1.  Checks if source exists.
//  2.  Checks file type.
//  3.  Checks extension.
//  4.  Keep ratio.
//  5.  Make smaller source images bigger.
//  6.  Save the image to the given destination path.

//It doesn't:
//  1.  Check if the destination image exists.
//  2.  Upload the image to the server.
//  3.  Crop
//*******************************************

// Summary:
// Function to resize an image ($dst_img) from the source image ($src_img) with
// the given height ($dst_h), width ($dst_w) and quality $dst_quality. The paths are where the
// source and destination image are saved or going to be saved.

//Script start here.

function resizeImage($src_img, $dst_img, $src_path, $dst_path, $dst_w, $dst_h, $dst_quality){

    //Stop and giving an error if the file does not exists.
    if(file_exists($src_path . basename($src_img)) == false){
        die('<p>Het bestand bestaat niet. Kijk of dat de afbeelding "' . $src_img . '" in het juiste pad "' . $src_path . '" is opgeslagen.</p>');
    }
    //Get variables for the function.
    //complete path of the source image.
    $src_cpl = $src_path . basename($src_img);
    //complete path of the destination image.
    $dst_cpl = $dst_path . basename($dst_img);
    //extension excl "." of the source image, in lowercase.
    $src_ext = strtolower(end(explode(".", $src_img)));
    //width and height sizes of the source image.
    list($src_w, $src_h) = getimagesize($src_cpl);

    //get type of image.
    $src_type = exif_imagetype($src_cpl);



    //Checking extension and imagetype of the source image and path.
    if( ($src_ext =="jpg") && ($src_type =="2") ){
        $src_img = imagecreatefromjpeg($src_cpl);
    }else if( ($src_ext =="jpeg") && ($src_type =="2") ){
        $src_img = imagecreatefromjpeg($src_cpl);
    }else if( ($src_ext =="gif") && ($src_type =="1") ){
        $src_img = imagecreatefromgif($src_cpl);
    }else if( ($src_ext =="png") && ($src_type =="3") ){
        $src_img = imagecreatefrompng($src_cpl);
    }else{
        die('<p>The file "'. $src_img . '" with the extension "' . $src_ext . '" and the imagetype "' . $src_type . '" is not a valid image. Please upload an image with the extension JPG, JPEG, PNG or GIF and has a valid image filetype.</p>');
    }

    //Get heights and width so the image keeps its ratio.
    $x_ratio = $dst_w / $src_w;
    $y_ratio = $dst_h / $src_h;

    if( (($x_ratio > 1) || ($y_ratio > 1)) && ($x_ratio > $y_ratio) ){
        //If one of the sizes of the image is smaller than the destination (normal: more height than width).
        $dst_w = ceil($y_ratio * $src_w);
        $dst_h = $dst_h;
    }elseif( (($x_ratio > 1) || ($y_ratio > 1)) && ($y_ratio > $x_ratio) ){
        //If one of the sizes of the image is smaller than the destination (landscape: more width than height).
        $dst_w = $dst_w;
        $dst_h = ceil($x_ratio * $src_h);
    }elseif (($x_ratio * $src_h) < $dst_h){
        //if the image is landscape (more width than height).
        $dst_h = ceil($x_ratio * $src_h);
        $dst_w = $dst_w;
    }elseif (($x_ratio * $src_h) > $dst_h){
        //if the image is normal (more height than width).
        $dst_h = ceil($x_ratio * $src_h);
        $dst_w = $dst_w;
    }else{
        //if the image is normal (more height than width).
        $dst_w = ceil($y_ratio * $src_w);
        $dst_h = $dst_h;
    }

    // Creating the resized image.
    $dst_img=imagecreatetruecolor($dst_w,$dst_h);
    imagecopyresampled($dst_img,$src_img,0,0,0,0,$dst_w, $dst_h,$src_w,$src_h);

    // Saving the resized image.
    imagejpeg($dst_img,$dst_cpl,$dst_quality);

    // Cleaning the memory.
    imagedestroy($src_img);
    imagedestroy($dst_img);

    //Script ends here.
}
?>