<?php
//Declaration des variables 
$currentDate;
$nextYear;
$errors = [];
$errorMessage;
$firstTime = "true";

///////////////////////////////////
////// GESTION DE FORMULAIRE //////
//////////////////////////////////

function calculateDate() {
    global $currentDate;
    global $nextYear;
    $date = getdate();
    if ($date["mon"] < 10) {
        $date["mon"] = "0".$date["mon"];
    }
    if ($date["mday"] < 10) {
        $date["mday"] = "0".$date["mday"];
    }
    $currentDate = $date["year"]."-".$date["mon"]."-".$date["mday"];
    $nextYear = intval($date["year"]+1)."-".$date["mon"]."-".$date["mday"];
}

function detectError($attendus) {
    global $currentDate;
    global $errorMessage;
    global $errors;
    if (array_key_exists("send", $_POST)) {
        foreach($attendus as $attendu) {
            if($_POST[$attendu] == "") {
                array_push($errors, $attendu);
            }
        }
        if(count($errors) > 1){
            $errorMessage = "Il y a une plusieurs erreurs dans le formulaire.";
        } elseif(count($errors) > 0){
            $errorMessage = "Il y a une erreur dans le champs $errors[0].";
        }else {
            if (array_key_exists("email",$_POST) && !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                array_push($errors, $_POST["email"]);
                $errorMessage = "Veuillez entrer une adresse email correcte.";
            }
        }
    }
}

function errorClass($input){
    global $errors;
    if(array_key_exists("send",$_POST)) {
        if (in_array($input, $errors)) {
            echo " redBorder";
        } elseif ($input == "email")
            if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            echo " redBorder";
        }
    }
}

function errorMessage(){
    global $errorMessage;
    if (!empty($errorMessage)) {
        echo "<p class=\"red\">$errorMessage</p>";
    }
}

function ancientValue($input) {
    global $sentToDB;
    if ($sentToDB == "false") {
        if (!empty($_POST[$input])) {
            echo "value=\"$_POST[$input]\"";
        }
    }
}

function generateOption($options) {
    if (array_key_exists("where", $_POST)) {
        echo "<option value=".$_POST["where"].">Baptême de l'air -".$_POST["where"]."</option>";
        foreach($options as $option) {
            if ($option !== $_POST["where"]) {
                echo "<option value=\"$option\">Baptême de l'air - $option</option>";
            }
        }
    } elseif (array_key_exists("type", $_POST)) {
        echo "<option value=".$_POST["type"].">Baptême de l'air -".$_POST["type"]."</option>";
        foreach($options as $option) {
            if ($option !== $_POST["type"]) {
                echo "<option value=\"$option\">Baptême de l'air - $option</option>";
            }
        }
     } else {
        foreach($options as $option) {
            echo "<option value=\"$option\">Baptême de l'air - $option</option>";
        }
    }
}

//////////////////
////// SQL //////
////////////////

function getInfoDB($sql, $parameters) {
    global $db;
    $prepare = $db->prepare($sql);
    if (!empty($parameters)) {
        $prepare->execute($parameters) or die ($db->errorinfo());
    } else {
        $prepare->execute() or die ($db->errorinfo());
    }
    $results = $prepare->fetchAll(PDO::FETCH_ASSOC);
    return $results;
}

function addToDB($sql, $parameters) {
    global $db;
    $prepare = $db->prepare($sql);
    if (!empty($parameters)) {
        $prepare->execute($parameters) or die ($db->errorinfo());
    } else {
        $prepare->execute() or die ($db->errorinfo());
    }}


/////////////////////
////// COOKIE //////
///////////////////

function checkFirstTime() {
    global $firstTime;
    if(array_key_exists("secondTime",$_POST)) {
        $firstTime = "false";
    }
}

function cookieData($input) {
    global $firstTime;
    if (array_key_exists("email", $_COOKIE) && $firstTime == "true") {
        echo "value=\"$_COOKIE[$input]\"";
    }
}
?>