<?php
session_start();
if(!array_key_exists("logged", $_SESSION)) {
    header("location:../../admin.php");
    exit();
}else {
    if($_SESSION["logged"] !== "true") {
        header("location:../../admin.php");
        exit();
    }
}

include "../database.php";
include "../functions.php";

if(array_key_exists("toDB", $_POST)) {
    if ($_POST["toDB"] == "Supprimer") {
        $supressSQL = "DELETE FROM reservation WHERE numReserv = :numReserv";
        $parameters = [
            "numReserv" => $_POST["numReserv"]
        ];
        addToDB($supressSQL, $parameters);
        foreach ($_POST as $key => $value) {
            $_POST[$key] = NULL;
        }
    } else {
        if($_POST["errorDB"] == 0) {
            $modifSQL = "UPDATE reservation set matricule = :matricule, identifiant = :identifiant, statut = \"Traité\" WHERE numReserv = :numReserv";
            $parameters = [
                "matricule" => $_POST["matricule"],
                "identifiant" => $_POST["identifiant"],
                "numReserv" => $_POST["numReserv"]
            ];
            addToDB($modifSQL, $parameters);
        }
    }
}


$getReservation;
$pageCourante;
if(array_key_exists("pageCourante", $_POST)) {
    $pageCourante = $_POST["pageCourante"];
} else {
    $pageCourante = 1;
}

$reservationParPage = 15;
$allReservation;
$depart = ($pageCourante - 1) * $reservationParPage;

if (array_key_exists("filter", $_POST)) {
    global $depart;
    global $reservationParPage;
    global $getReservation;
    global $allReservation;
    if ($_POST["filter"] == "Attente") {

        $getReservation = "SELECT * FROM reservation WHERE statut=\"En attente\" ORDER BY numReserv ASC LIMIT ".$depart.",".$reservationParPage.";";
        $reservationSqlDB = "SELECT COUNT(numReserv) FROM reservation WHERE statut=\"En attente\"";
        $results = getInfoDB($reservationSqlDB,"");
        $allReservation = $results[0]["COUNT(numReserv)"];

    } elseif ($_POST["filter"] == "Traite") {

        $getReservation = "SELECT * FROM reservation WHERE statut=\"Traite\" ORDER BY numReserv ASC LIMIT ".$depart.",".$reservationParPage.";";
        $reservationSqlDB = "SELECT COUNT(numReserv) FROM reservation WHERE statut=\"Traite\"";
        $results = getInfoDB($reservationSqlDB,"");
        $allReservation = $results[0]["COUNT(numReserv)"];

    } elseif ($_POST["filter"] == ""){

        $getReservation = "SELECT * FROM reservation ORDER BY numReserv ASC LIMIT ".$depart.",".$reservationParPage.";";
        $reservationSqlDB = "SELECT COUNT(numReserv) FROM reservation";
        $results = getInfoDB($reservationSqlDB,"");
        $allReservation = $results[0]["COUNT(numReserv)"];

    }else {

        $getReservation = "SELECT * FROM reservation WHERE numReserv=\"".$_POST["filter"]."\" ORDER BY numReserv ASC LIMIT ".$depart.",".$reservationParPage.";";
        $reservationSqlDB = "SELECT COUNT(numReserv) FROM reservation WHERE numReserv=\"".$_POST["filter"]."\"";
        $results = getInfoDB($reservationSqlDB,"");
        $allReservation = $results[0]["COUNT(numReserv)"];

    }

} else {

    global $depart;
    global $reservationParPage;
    global $allReservation;
    global $getReservation;
    $getReservation = "SELECT * FROM reservation ORDER BY numReserv ASC LIMIT ".$depart.",".$reservationParPage.";";
    $reservationSqlDB = "SELECT COUNT(numReserv) FROM reservation";
    $results = getInfoDB($reservationSqlDB,"");
    $allReservation = $results[0]["COUNT(numReserv)"];

}

$pagesTotales = ceil($allReservation/$reservationParPage); 
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
        <link rel="icon" type="../../image/x-icon" href="../images/logo.png">
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
            <div class="list w-100 d-flex flex-column <?php if(count($results) <= 0) { echo "justify-content-center";}?> align-items-center">
                <?php
                if(count($results) > 0) {
                    foreach($results as $reservation) {
                        $dateExploded = explode("-", $reservation["dateReserv"]);
                        $date = $dateExploded[2]."/".$dateExploded[1]."/".$dateExploded[0];
                        echo '
                        <article class="infoContainer bg-white d-flex align-items-center">
                            <div class="numeroReservation borderRight sections d-flex flex-column justify-content-between">
                                <h3>N°Reservation</h3>
                                <div>
                                    <p>N°'.$reservation["numReserv"].'</p>
                                    <hr>
                                </div>
                            </div>
                            <div class="adresseMail borderRight sections d-flex flex-column justify-content-between">
                                <h3>Adresse email</h3>
                                <div>
                                    <p>'.$reservation["email"].'</p>
                                    <hr>
                                </div>
                            </div>
                            <div class="dateReservation borderRight sections d-flex flex-column justify-content-between">
                                <h3>Date de réservation</h3>
                                <div>
                                    <p>'.$date.'</p>
                                    <hr>
                                </div>
                            </div>
                            <div class="typeULM borderRight sections d-flex flex-column justify-content-between">
                                <h3>Type d\'ULM</h3>
                                <div>
                                    <p>'.$reservation["type"].'</p>
                                    <hr>
                                </div>
                            </div>
                             <div class="statutReservation sections d-flex flex-column justify-content-between align-items-center">
                                <h3>Statut de réservation</h3>
                                <div>
                                    <form method="post" action="reservationList.php">
                                        <input type="hidden" name="numReserv" value="'.$reservation["numReserv"].'">
                                        <input type="hidden" name="email" value="'.$reservation["email"].'">
                                        <input type="hidden" name="dateReserv" value="'.$reservation["dateReserv"].'">
                                        <input type="hidden" name="type" value="'.$reservation["type"].'">
                                        <input type="hidden" name="statut" value="'.$reservation["statut"].'">
                                        <button class="'.$reservation["statut"].'" type="submit" name="more">'.$reservation["statut"].'</button>
                                    </form>
                                </div>
                            </div>
                        </article>
                        ';
                    }
                } else {
                    echo '
                    <article class="infoContainer notFound bg-white d-flex justify-content-center align-items-center">
                        <p>Aucune réservation n\'a été trouvé.</p>
                    </article>
                    ';
                }
                ?>
            </div>
            <div class="bg-white pagination w-100 d-flex justify-content-center align-items-center">
                <?php
                    for ($i = 1; $i <= $pagesTotales; $i++) {
                        if(array_key_exists("filter", $_POST) && $_POST["filter"] !== "") {
                            if($i == $pageCourante) {
                                echo '
                                <form method="post" action="reservationList.php">
                                    <input type="hidden" value="'.$i.'" name="pageCourante">
                                    <input type="hidden" value="'.$_POST["filter"].'" name="filter">
                                    <button class="currentPage" type="submit">'.$i.'</button>
                                </form>
                                ';
                                
                            }else {
                                echo '
                                <form method="post" action="reservationList.php">
                                    <input type="hidden" value="'.$i.'" name="pageCourante">
                                    <input type="hidden" value="'.$_POST["filter"].'" name="filter">
                                    <button type="submit">'.$i.'</button>
                                </form>
                                ';
                            }
                        } else {
                            if($i == $pageCourante) {
                                echo '
                                <form method="post" action="reservationList.php">
                                    <input type="hidden" value="'.$i.'" name="pageCourante">
                                    <button class="currentPage" type="submit">'.$i.'</button>
                                </form>
                            ';
                            } else {
                                echo '
                                <form method="post" action="reservationList.php">
                                    <input type="hidden" value="'.$i.'" name="pageCourante">
                                    <button type="submit">'.$i.'</button>
                                </form>
                                ';
                            }
                        }
                    }
                ?>
            </div>
        </section>
        <?php
        if(array_key_exists("more", $_POST) && $_POST["more"] !== NULL) {
            $dateExploded = explode("-", $_POST["dateReserv"]);
            $date = $dateExploded[2]."/".$dateExploded[1]."/".$dateExploded[0];
            
            //Get all pilote in DB with a certain reservation date
            $getAllPiloteAtDate = "SELECT identifiant FROM reservation WHERE type=\"".$_POST["type"]."\" AND statut=\"Traité\" AND dateReserv=\"".$_POST["dateReserv"]."\";";
            $results = getInfoDB($getAllPiloteAtDate, "");
            $allPiloteAtDate = [];
            foreach($results as $piloteID) {
                array_push($allPiloteAtDate, $piloteID["identifiant"]);
            }

            //Get all pilote in DB with a certain type
            $getAllPiloteCertainType = "SELECT identifiant FROM pilote WHERE type=\"".$_POST["type"]."\";";
            $results = getInfoDB($getAllPiloteCertainType, "");
            $allPiloteCertainType = [];
            foreach($results as $piloteCertainType) {
                array_push($allPiloteCertainType, $piloteCertainType["identifiant"]);
            }

            //Pilotes that are free
            $piloteFree = [];
            foreach($allPiloteCertainType as $piloteCertainType) {
                if(!in_array($piloteCertainType, $allPiloteAtDate)) {
                    array_push($piloteFree, $piloteCertainType);
                }
            }

            //Pilotes that are free with name
            $piloteFreeWithName = [];
            foreach($piloteFree as $piloteID) {
                $getNameSQL = "SELECT nom FROM staff WHERE identifiant=\"".$piloteID."\";";
                $result = getInfoDB($getNameSQL, "");
                array_push($piloteFreeWithName, $result[0]["nom"]);
            }

            //Get all véhicule in DB wisth a certain reservation date
            $getAllVehiculeAtDate = "SELECT matricule FROM reservation WHERE type=\"".$_POST["type"]."\" AND statut=\"Traité\" AND dateReserv=\"".$_POST["dateReserv"]."\";";
            $results = getInfoDB($getAllVehiculeAtDate, "");
            $allVehiculeAtDate = [];
            foreach($results as $vehiculeID) {
                array_push($allVehiculeAtDate, $vehiculeID["matricule"]);
            }
            
            //Get all véhicule in DB with a certain type
            $getAllVehiculeCertainType = "SELECT matricule FROM vehicule WHERE type=\"".$_POST["type"]."\";";
            $results = getInfoDB($getAllVehiculeCertainType, "");
            $allVehiculeCertainType = [];
            foreach($results as $vehiculeCertainType) {
                array_push($allVehiculeCertainType, $vehiculeCertainType["matricule"]);
            }
            
            //Vehicule that are free
            $vehiculeFree = [];
            foreach($allVehiculeCertainType as $vehiculeCertainType) {
                if(!in_array($vehiculeCertainType, $allVehiculeAtDate)) {
                    array_push($vehiculeFree, $vehiculeCertainType);
                }
            }

            calculateDate();
            $errorInDB;            

            // Test if the reservation is possible
            if (count($vehiculeFree) == 0 || count($piloteFree) == 0 || $_POST["dateReserv"] < $currentDate){
                $errorInDB = 1;
            } else {
                $errorInDB = 0;
            }

            //If Traite and not En attente
            $matricule;
            $piloteID;
            $piloteName;

            if($_POST["statut"] == "Traité"){
                $getMatriculeAndPilote = "SELECT matricule, identifiant FROM reservation WHERE numReserv=:numReserv";
                $parameters = [
                    "numReserv" => $_POST["numReserv"]
                ];
                $results = getInfoDB($getMatriculeAndPilote, $parameters);
                $matricule = $results[0]["matricule"];
                $piloteID = $results[0]["identifiant"];

                $getPiloteName = "SELECT nom FROM staff WHERE identifiant=\"".$piloteID."\";";
                $result = getInfoDB($getPiloteName, "");
                $piloteName = $result[0]["nom"];
            }
            ?>
            <section id="popUp" class="popUpContainer d-flex justify-content-center align-items-center w-100">
                <form class="d-flex flex-column align-items-center" method="post" action="reservationList.php">
                    <img class="closePopUp" src="../../images/close.png" onClick="document.getElementById('popUp').classList.add('none');">
                    <h2 class="w-100 text-center">Réservation N°<?php echo $_POST["numReserv"]?></h2>
                    <div class="popUpInfo d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-column align-items-center">
                            <h2>Email de l'adhérent</h2>
                            <p><?php echo $_POST["email"]?></p>
                        </div>
                        <div class="d-flex flex-column align-items-center">
                            <h2>Type de véhicule</h2>
                            <p><?php echo $_POST["type"];?></p>
                        </div>
                    </div>
                    <div class="popUpInfo d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-column align-items-center">
                            <h2>Matricule du véhicule</h2>
                            <?php
                            if($_POST["statut"] == "Traité") {
                                ?>
                                <select readonly name="matricule">
                                    <option value="<?php echo $matricule; ?>"><?php echo $matricule ?></option>
                                </select>
                                <?php
                            } else {
                                ?>
                                <select name="matricule">
                                    <?php generateOptionMatricule(); ?>
                                </select>
                                <?php
                            }
                            ?>
                        </div>
                        <div class="d-flex flex-column align-items-center">
                            <h2>Pilote du véhicule</h2>
                            <?php
                            if($_POST["statut"] == "Traité") {
                                ?>
                                <select readonly="readonly" name="identifiant">
                                    <option value="<?php echo $piloteID; ?>"><?php echo $piloteName ?></option>
                                </select>
                                <?php
                            } else {
                                ?>
                                <select name="identifiant">
                                    <?php generateOptionPilote(); ?>
                                </select>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="popUpInfo m-0 d-flex justify-content-between align-items-center">
                        <div class="d-flex solo flex-column align-items-center">
                            <h2>Date de réservation</h2>
                            <p><?php echo $date;?></p>
                        </div>
                    </div>
                    <div class="sendToDB d-flex w-100 justify-content-center align-items-center">
                        <input type="hidden" name="numReserv" value="<?php echo $_POST["numReserv"]?>">
                        <?php
                            if($_POST["statut"] == "En attente") {
                                ?>
                                    <button class="sendDB supprimer d-flex justify-content-center align-items-center" name="toDB" value="Supprimer">Supprimer</button>
                                <?php
                            }
                        ?>
                        <button class="sendDB valider d-flex justify-content-center align-items-center" name="toDB" value="Valider">Valider</button>
                        <?php
                        if ($errorInDB == 1) {
                            echo '<input type="hidden" name="numReserv" value="'.$_POST["numReserv"].'">';
                            echo '<input type="hidden" name="email" value="'.$_POST["email"].'">';
                            echo '<input type="hidden" name="dateReserv" value="'.$_POST["dateReserv"].'">';
                            echo '<input type="hidden" name="type" value="'.$_POST["type"].'">';
                            echo '<input type="hidden" name="statut" value="'.$_POST["statut"].'">';
                            echo '<input type="hidden" name="more">';
                            echo '<input type="hidden" name="errorDB" value="1">';
                            if($_POST["statut"] !== "Traité") {
                                echo '<p>La réservation est impossible.</p>';
                            }
                        } else {
                            echo '<input type="hidden" name="errorDB" value="0">';
                        }
                        ?>
                    </div>
                </form>
            </section>
        <?php
         }
        ?>       
    </body>
</htlm>
