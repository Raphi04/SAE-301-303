<?php
session_start();
if(!array_key_exists("logged", $_SESSION)) {
    header("location:../admin.php");
    exit();
}else {
    if($_SESSION["logged"] !== "true") {
        header("location:../admin.php");
        exit();
    }
}

include "../database.php";
include "../functions.php";

$getReservation;

if (array_key_exists("filter", $_POST)) {
    global $getReservation;
    if ($_POST["filter"] == "Attente") {
        $getReservation = "SELECT * FROM reservation WHERE statut=\"Attente\"";
    } elseif ($_POST["filter"] == "Traite") {
        $getReservation = "SELECT * FROM reservation WHERE statut=\"Traite\"";
    } elseif ($_POST["filter"] == "Tout"){
        $getReservation = "SELECT * FROM reservation";
    } elseif ($_POST["filter"] == ""){
        $getReservation = "SELECT * FROM reservation";
    }else {
        $getReservation = "SELECT * FROM reservation WHERE numReserv=\"".$_POST["filter"]."\"";
    }
} else {
    global $getReservation;
    $getReservation = "SELECT * FROM reservation;";
}
$results = getInfoDB($getReservation, "");
?>
<!DOCTYPE html>
<html lang="fr" class="h-100 w-100">
    <head>
        <!-- Meta -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Raphael CADETE, Hugo BAJOUE, Yanis WONG">
    
        <title>ACF2L - Backoffice - Réservation</title>

        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <link href="../../css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link rel="stylesheet" href="../../css/backoffice/basics.css">
        <link rel="stylesheet" href="../../css/backoffice/reservation.css">
        <link rel="icon" type="image/x-icon" href="../images/logo.png">
    </head>
    <body class="w-100 h-100">
        <header class="w-100 bg-white d-flex justify-content-between align-items-center">
            <h1>Réservation</h1>
            <div class="buttonContainer d-flex align-items-center">
                <form class="m-0" action="reservationList.php" method="post">
                    <input type="hidden" name="filter" value="Attente">
                    <button class="button" type="submit">En attente</button>
                </form>
                <form class="m-0" action="reservationList.php" method="post">
                    <input type="hidden" name="filter" value="Traite">
                    <button id="traite" class="button" type="submit">Traité</button>
                </form>
                <form class="m-0" action="reservationList.php" method="post">
                    <input class="recherche" type="text" name="filter" placeholder="Recherche par N°">
                    <button class="button recherche" type="submit">Recherche</button>
                </form>
            </div>
        </header>
        <section class="contentContainer w-100 d-flex flex-column justify-content-center align-items-center">
            <div class="list w-100 d-flex flex-column align-items-center">
                <?php
                if(count($results) > 0) {
                    foreach($results as $reservation) {
                        echo '
                        <article class="infoContainer bg-white d-flex align-items-center">
                            <div id="numeroReservation" class="borderRight d-flex flex-column justify-content-between">
                                <h3>N°Reservation</h3>
                                <div>
                                    <p>N°'.$reservation["numReserv"].'</p>
                                    <hr>
                                </div>
                            </div>
                            <div id="adresseMail" class="borderRight d-flex flex-column justify-content-between">
                                <h3>Adresse email</h3>
                                <div>
                                    <p>'.$reservation["mail"].'</p>
                                    <hr>
                                </div>
                            </div>
                            <div id="dateReservation" class="borderRight d-flex flex-column justify-content-between">
                                <h3>Date de réservation</h3>
                                <div>
                                    <p>'.$reservation["dateReserv"].'</p>
                                    <hr>
                                </div>
                            </div>
                            <div id="typeULM" class="borderRight d-flex flex-column justify-content-between">
                                <h3>Type d\'ULM</h3>
                                <div>
                                    <p>'.$reservation["type"].'</p>
                                    <hr>
                                </div>
                            </div>
                             <div id="statutReservation" class="borderRight d-flex flex-column justify-content-between align-items-center">
                                <h3>Statut de réservation</h3>
                                <div>
                                    <form method="post" action="reservationList.php">
                                        <input type="hidden" name="numReserv" value="'.$reservation["numReserv"].'">
                                        <input type="hidden" name="email" value="'.$reservation["numReserv"].'">
                                        <input type="hidden" name="dateReserv" value="'.$reservation["numReserv"].'">
                                        <input type="hidden" name="type" value="'.$reservation["numReserv"].'">
                                        <button class="'.$reservation["statut"].'" type="subtmit" name="more">'.$reservation["statut"].'</button>
                                    </form>
                                </div>
                            </div>
                        </article>
                        ';
                    }
                } else {
                    echo '
                    <article class="infoContainer bg-white">
                        <p>Aucune réservation n\'a été trouvé.</p>
                    </article>
                    ';
                }
                ?>
            </div>
            <div class="bg-white pagination w-100 d-flex justify-content-center align-items-center">
                <div>
                    1 -
                </div>
                <div>
                    1 -
                </div>
                <div>
                    1
                </div>
            </div>
        </section>
    </body>
</htlm>
