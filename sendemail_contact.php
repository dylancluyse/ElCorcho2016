<?php

$to = "dylan.cluyse@outlook.com";
$subject = $_POST['subject'];

$message = 'EL CORCHO CONTACT
------------------------------------
Naam: '.$_POST['name'].'
Email: ' . $_POST['email'] . '
------------------------------------
' . $_POST['message'];

$headers = "From: contact@elcorcho.be\r\n";
$headers .= "X-Sender: <contact@elcorcho.be>\r\n";
$headers .= "X-Mailer: PHP \r\n";
$headers .= "X-Priority: 1\r\n"; //1 is spoedbericht, 3 is normaal bericht;
$headers .= "Return-Path: <contact@elcorcho.be>\r\n";

//terugsturen naar formlogin.php
if (mail($to, $subject, $message, $headers)) {
    header('location:index.php');
} else {
    header('location:index.php?error=ml');
}


?>