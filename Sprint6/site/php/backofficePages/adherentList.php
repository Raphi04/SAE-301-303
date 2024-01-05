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

$errorInDB;
$errorMessage;

if(array_key_exists("toDB", $_POST)) {

    if ($_POST["toDB"] == "Supprimer") {
        $supressSQL = "DELETE FROM adherent WHERE email = :email";
        $parameters = [
            "email" => $_POST["email"]
        ];
        addToDB($supressSQL, $parameters);
        foreach ($_POST as $key => $value) {
            $_POST[$key] = NULL;
         }

    } else {
        global $errorInDB;
        global $errorMessage;
        
        //Get all email from DB
        $emailInDB = [];
        $getEmailInDB = "SELECT email FROM adherent";
        $results = getInfoDB($getEmailInDB, "");
        foreach ($results as $result) {
            array_push($emailInDB, $result["email"]);
        }

        //Check if empty
        $attendus = ["email", "prenom", "nom", "telephone"];
        $empty = [];
        foreach($attendus as $attendu) {
            if($_POST[$attendu] == "") {
                array_push($empty, $attendu);
            }
        }

        //Check errors
        if (count($empty) > 0) {
            
            $errorInDB = 1;
            $errorMessage = "Tous les champs doivent être rempli";
            
        } elseif (in_array($_POST["email"], $emailInDB)) {
            $errorInDB = 1;
            $errorMessage = "Adresse email déjà attribué";
            
        } else {
            $errorInDB = 0;
        }

        if($_POST["errorDB"] == 0) {
            $modifSQL = "UPDATE adherent set email = :email, prenom = :prenom, nom = :nom, telephone = :telephone WHERE email = :emailOriginal";
            $parameters = [
                "email" => $_POST["email"],
                "prenom" => $_POST["prenom"],
                "nom" => $_POST["nom"],
                "telephone" => $_POST["telephone"],
                "emailOriginal" => $_POST["emailOriginal"]
            ];
            addToDB($modifSQL, $parameters);
            foreach ($_POST as $key => $value) {
                $_POST[$key] = NULL;
            }
        }
    }
}


$getAdherent;
$pageCourante;
if(array_key_exists("pageCourante", $_POST)) {
    $pageCourante = $_POST["pageCourante"];
} else {
    $pageCourante = 1;
}

$adherentParPage = 15;
$allAdherent;
$depart = ($pageCourante - 1) * $adherentParPage;

if (array_key_exists("filter", $_POST)) {
    global $depart;
    global $adherentParPage;
    global $getAdherent;
    global $allAdherent;
    if ($_POST["filter"] == ""){

        $getAdherent = "SELECT * FROM adherent ORDER BY email ASC LIMIT ".$depart.",".$adherentParPage.";";
        $adherentSqlDB = "SELECT COUNT(email) FROM adherent";
        $results = getInfoDB($adherentSqlDB,"");
        $allAdherent = $results[0]["COUNT(email)"];

    }else {

        $getAdherent = "SELECT * FROM adherent WHERE email=\"".$_POST["filter"]."\" ORDER BY email ASC LIMIT ".$depart.",".$adherentParPage.";";
        $adherentSqlDB = "SELECT COUNT(email) FROM adherent WHERE email=\"".$_POST["filter"]."\"";
        $results = getInfoDB($adherentSqlDB,"");
        $allAdherent = $results[0]["COUNT(email)"];

    }

} else {

    global $depart;
    global $adherentParPage;
    global $allAdherent;
    global $getAdherent;
    $getAdherent = "SELECT * FROM adherent ORDER BY email ASC LIMIT ".$depart.",".$adherentParPage.";";
    $adherentSqlDB = "SELECT COUNT(email) FROM adherent";
    $results = getInfoDB($adherentSqlDB,"");
    $allAdherent = $results[0]["COUNT(email)"];

}

$pagesTotales = ceil($allAdherent/$adherentParPage); 
$results = getInfoDB($getAdherent, "");
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
        <link rel="stylesheet" href="../../css/backoffice/adherent.css">
        <link rel="icon" type="image/x-icon" href="../images/logo.png">
    </head>
    <body class="w-100 h-100">
        <header class="w-100 bg-white d-flex justify-content-between align-items-center">
            <h1>Adherent</h1>
            <div class="buttonContainer d-flex align-items-center">
                <form class="m-0" action="adherentList.php" method="post">
                    <input class="recherche" type="text" name="filter" placeholder="Recherche par email">
                    <button class="button recherche" type="submit">Recherche</button>
                </form>
            </div>
        </header>
        <section class="contentContainer w-100 d-flex flex-column justify-content-center align-items-center">
            <div class="list w-100 d-flex flex-column <?php if(count($results) <= 0) { echo "justify-content-center";}?> align-items-center">
                <?php
                if(count($results) > 0) {
                    foreach($results as $adherent) {
                        echo '
                        <article class="infoContainer bg-white d-flex align-items-center">
                            <div class="emailAdherent borderRight sections d-flex flex-column justify-content-between">
                                <h3>Email</h3>
                                <div>
                                    <p>'.$adherent["email"].'</p>
                                    <hr>
                                </div>
                            </div>
                            <div class="adherentPrenom borderRight sections d-flex flex-column justify-content-between">
                                <h3>Prenom</h3>
                                <div>
                                    <p>'.$adherent["prenom"].'</p>
                                    <hr>
                                </div>
                            </div>
                            <div class="adherentNom borderRight sections d-flex flex-column justify-content-between">
                                <h3>Nom</h3>
                                <div>
                                    <p>'.$adherent["nom"].'</p>
                                    <hr>
                                </div>
                            </div>
                            <div class="adherentTelephone borderRight sections d-flex flex-column justify-content-between">
                                <h3>Téléphone</h3>
                                <div>
                                    <p>'.$adherent["telephone"].'</p>
                                    <hr>
                                </div>
                            </div>
                             <div class="adherentModify sections d-flex flex-column justify-content-between align-items-center">
                                <h3>Modifier les informations</h3>
                                <div>
                                    <form method="post" action="adherentList.php">
                                        <input type="hidden" name="email" value="'.$adherent["email"].'">
                                        <input type="hidden" name="prenom" value="'.$adherent["prenom"].'">
                                        <input type="hidden" name="nom" value="'.$adherent["nom"].'">
                                        <input type="hidden" name="telephone" value="'.$adherent["telephone"].'">
                                        <button type="submit" name="modify">Modifier</button>
                                    </form>
                                </div>
                            </div>
                        </article>
                        ';
                    }
                } else {
                    echo '
                    <article class="infoContainer notFound bg-white d-flex justify-content-center align-items-center">
                        <p>Aucun(e) Adhérent(e) n\'a été trouvé(e).</p>
                    </article>
                    ';
                }
                ?>
            </div>
            <div class="bg-white pagination w-100 d-flex justify-content-center align-items-center">
                <?php
                    for ($i = 1; $i <= $pagesTotales; $i++) {
                        if($i == $pageCourante) {
                            echo '
                            <form method="post" action="adherentList.php">
                                <input type="hidden" value="'.$i.'" name="pageCourante">
                                <button class="currentPage" type="submit">'.$i.'</button>
                            </form>
                        ';
                        } else {
                            echo '
                            <form method="post" action="adherentList.php">
                                <input type="hidden" value="'.$i.'" name="pageCourante">
                                <button type="submit">'.$i.'</button>
                            </form>
                            ';
                        }
                    }
                ?>
            </div>
        </section>
        <?php
        if(array_key_exists("modify", $_POST) && $_POST["modify"] !== NULL) {
            global $errorInDB;
            global $errorMessage;
            ?>
            <section id="popUp" class="popUpContainer d-flex justify-content-center align-items-center w-100">
                <form class="d-flex flex-column align-items-center" method="post" action="adherentList.php">
                    <img class="closePopUp" src="../../images/close.png" onClick="document.getElementById('popUp').classList.add('none');">
                    <h2 class="w-100 text-center">Adhérent : <?php echo $_POST["prenom"]?> <?php echo $_POST["nom"]?></h2>
                    <div class="popUpInfo d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-column align-items-center">
                            <h2>Prenom de l'adhérent</h2>
                            <input type="text" name="prenom" value="<?php echo $_POST["prenom"]?>">
                        </div>
                        <div class="d-flex flex-column align-items-center">
                            <h2>Nom de l'adhérent</h2>
                            <input type="text" name="nom" value="<?php echo $_POST["nom"]?>">
                        </div>
                    </div>
                    <div class="popUpInfo d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-column align-items-center">
                            <h2>Adresse email</h2>
                            <input type="text" name="email" value="<?php echo $_POST["email"]?>">
                        </div>
                        <div class="d-flex flex-column align-items-center">
                            <h2>Téléphone</h2>
                            <input type="number" name="telephone" value="<?php echo $_POST["telephone"]?>">
                        </div>
                    </div>
                    <div class="sendToDB d-flex w-100 justify-content-center align-items-center">
                        <input type="hidden" name="emailOriginal" value="<?php echo $_POST["email"]?>">
                        <input type="hidden" name="modify">
                        <button class="sendDB supprimer d-flex justify-content-center align-items-center" name="toDB" value="Supprimer">Supprimer</button>
                        <button class="sendDB valider d-flex justify-content-center align-items-center" name="toDB" value="Valider">Valider</button>
                        <?php
                        if ($errorInDB == 1) {
                            echo '<input type="hidden" name="modify">';
                            echo '<input type="hidden" name="errorDB" value="1">';
                            echo '<p>'.$errorMessage.'</p>';
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
