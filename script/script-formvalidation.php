<?php
function verplicht($val,$key){
    //variabele buiten de functie beschikbaar maken
    global $message;

    if(empty($val)){
        $message[$key]='Dit is verplicht in te vullen';
    }
}

//functie die controleert of een waarde numeriek is
function isnumeric($val,$key,$verplicht,$lengte)
{
    global $message;

    //als $verplicht 1 is dan controle uitvoeren of veld numeriek is
    if (!empty($val) || $verplicht != 0)
    {
        if((bool)preg_match('/^[-+]?[0-9]*.?[0-9]+$/', $val))
        {
            if(strlen($val) != $lengte){
                $message[$key] = 'Dit getal moet ' . $lengte . ' teken(s) lang zijn';
            }

        } else {
            $message[$key] = 'Gelieve enkel cijfers in te vullen';
        }
    }
}


function lengte($val,$key,$lengte,$action){

    global $message;

    switch($action){

        case 'max':
            if(strlen($val) > $lengte){
                $message[$key] = 'Je mag maximum ' . $lengte . ' teken(s) invoeren';
            }
            break;

        case 'min':
            if(strlen($val) < $lengte){
                $message[$key] = 'Je moet minimum ' . $lengte . ' teken(s) invoeren';
            }
            break;

        case 'eq':
            if(strlen($val) != $lengte){
                $message[$key] = 'Je moet exact ' . $lengte . ' teken(s) invoeren';
            }
            break;
    }

}

function ismail($email)
{
    global $message;

    //filter_validate_email controleer of een e-mailadres valid is
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//        // ok
//        list($user,$domaine)=explode("@",$email,2);
//        //checkdnsrr kijkt of domeinnaam wel degelijk bestaat en of ze van het juiste type zijn
//        if(!checkdnsrr($domaine,"MX")&& !checkdnsrr($domaine,"A")){
//            $message['email'] = 'E-mail is ok, maar domeinnaam niet.';
//        }
//    }
//    else {
        //no
        $message['mail'] = 'Ongeldig e-mailadres';
    }

}
?>