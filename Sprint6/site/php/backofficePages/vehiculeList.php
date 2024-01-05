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
        $supressSQL = "DELETE FROM vehicule WHERE matricule = :matricule";
        $parameters = [
            "matricule" => $_POST["matricule"]
        ];
        addToDB($supressSQL, $parameters);
        foreach ($_POST as $key => $value) {
            $_POST[$key] = NULL;
         }
    } else {
        global $errorInDB;
        global $errorMessage;

        //Check if empty
        $attendus = ["matricule", "type"];
        $empty = [];
        foreach($attendus as $attendu) {
            if($_POST[$attendu] == "") {
                array_push($empty, $attendu);
            }
        }

        //Get all matricule from DB
        $matriculeInDB = [];
        $getMatriculeInDB = "SELECT matricule FROM vehicule";
        $results = getInfoDB($getMatriculeInDB, "");
        foreach ($results as $result) {
            array_push($matriculeInDB, $result["matricule"]);
        }
        
        //Check errors
        if (count($empty) > 0) {
            $errorInDB = 1;
            $errorMessage = "Les champs doivent être rempli";
        } elseif (in_array($_POST["matricule"], $matriculeInDB)) {
            if (array_key_exists("matriculeOriginal",$_POST) && $_POST["matricule"] == $_POST["matriculeOriginal"]) {
                $errorInDB = 0;
            }else {
                $errorInDB = 1;
                $errorMessage = "Matricule déjà attribué";
            }
        }else {
            $errorInDB = 0;
        }
        if ($errorInDB == 0) {
            if(array_key_exists("ajout", $_POST)) {
                $addVehicule = "INSERT INTO vehicule (matricule, type) VALUES (:matricule, :type)";
                $parameters = [
                    "matricule" => $_POST["matricule"],
                    "type" => $_POST["type"]
                ];
                addToDB($addVehicule, $parameters);
                $_POST["ajout"] = NULL;
            } else {
                $modifVehicule = "UPDATE vehicule SET matricule = :matricule, type = :type WHERE matricule = :matriculeOriginal;";
                $parameters = [
                    "matricule" => $_POST["matricule"],
                    "type" => $_POST["type"],
                    "matriculeOriginal" => $_POST["matriculeOriginal"]
                ];
                addToDB($modifVehicule, $parameters);
                
                $_POST["modify"] = NULL;
            }
        }  
    }
}


$getVehicule;
$pageCourante;
if(array_key_exists("pageCourante", $_POST)) {
    $pageCourante = $_POST["pageCourante"];
} else {
    $pageCourante = 1;
}

$vehiculeParPage = 15;
$allVehicule;
$depart = ($pageCourante - 1) * $vehiculeParPage;

if (array_key_exists("filter", $_POST)) {
    global $depart;
    global $vehiculeParPage;
    global $getVehicule;
    global $allVehicule;

    if ($_POST["filter"] == "Pendulaire") {
        
        $getVehicule = "SELECT * FROM vehicule WHERE type=\"Pendulaire\" ORDER BY matricule ASC LIMIT ".$depart.",".$vehiculeParPage.";";
        $vehiculeSqlDB = "SELECT COUNT(matricule) FROM vehicule";
        $results = getInfoDB($vehiculeSqlDB,"");
        $allVehicule = $results[0]["COUNT(matricule)"];
        
    }elseif ($_POST["filter"] == "AutoGire"){
        
        $getVehicule = "SELECT * FROM vehicule WHERE type=\"AutoGire\" ORDER BY matricule ASC LIMIT ".$depart.",".$vehiculeParPage.";";
        $vehiculeSqlDB = "SELECT COUNT(matricule) FROM vehicule";
        $results = getInfoDB($vehiculeSqlDB,"");
        $allVehicule = $results[0]["COUNT(matricule)"];
        
    }elseif ($_POST["filter"] == "Axes"){
        
        $getVehicule = "SELECT * FROM vehicule WHERE type=\"Axes\" ORDER BY matricule ASC LIMIT ".$depart.",".$vehiculeParPage.";";
        $vehiculeSqlDB = "SELECT COUNT(matricule) FROM vehicule";
        $results = getInfoDB($vehiculeSqlDB,"");
        $allVehicule = $results[0]["COUNT(matricule)"];
        
    }elseif ($_POST["filter"] == ""){
        
        $getVehicule = "SELECT * FROM vehicule ORDER BY matricule ASC LIMIT ".$depart.",".$vehiculeParPage.";";
        $vehiculeSqlDB = "SELECT COUNT(matricule) FROM vehicule";
        $results = getInfoDB($vehiculeSqlDB,"");
        $allVehicule = $results[0]["COUNT(matricule)"];
        
    }else {
    
        $getVehicule = "SELECT * FROM vehicule WHERE matricule=\"".$_POST["filter"]."\" ORDER BY matricule ASC LIMIT ".$depart.",".$vehiculeParPage.";";
        $vehiculeSqlDB = "SELECT COUNT(matricule) FROM vehicule WHERE matricule=\"".$_POST["filter"]."\"";
        $results = getInfoDB($vehiculeSqlDB,"");
        $allVehicule = $results[0]["COUNT(matricule)"];

    }

} else {

    global $depart;
    global $vehiculeParPage;
    global $allVehicule;
    global $getVehicule;
    $getVehicule = "SELECT * FROM vehicule ORDER BY matricule ASC LIMIT ".$depart.",".$vehiculeParPage.";";
    $vehiculeSqlDB = "SELECT COUNT(matricule) FROM vehicule";
    $results = getInfoDB($vehiculeSqlDB,"");
    $allVehicule = $results[0]["COUNT(matricule)"];

}

$pagesTotales = ceil($allVehicule/$vehiculeParPage); 
$results = getInfoDB($getVehicule, "");
?>
<!DOCTYPE html>
<html lang="fr" class="h-100 w-100">
    <head>
        <!-- Meta -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Raphael CADETE, Hugo BAJOUE, Yanis WONG">
    
        <title>ACF2L - Backoffice - Véhicule</title>

        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <link href="../../css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link rel="stylesheet" href="../../css/backoffice/basics.css">
        <link rel="stylesheet" href="../../css/backoffice/vehicule.css">
        <link rel="icon" type="../../image/x-icon" href="../images/logo.png">
    </head>
    <body class="w-100 h-100">
        <header class="w-100 bg-white d-flex justify-content-between align-items-center">
            <h1>Véhicules</h1>
            <div class="buttonContainer d-flex align-items-center">
                <form class="m-0" action="vehiculeList.php" method="post">
                    <input type="hidden" name="filter" value="Pendulaire">
                    <button class="button" type="submit">Pendulaire</button>
                </form>
                <form class="m-0" action="vehiculeList.php" method="post">
                    <input type="hidden" name="filter" value="AutoGire">
                    <button class="button" type="submit">AutoGire</button>
                </form>
                <form class="m-0" action="vehiculeList.php" method="post">
                    <input type="hidden" name="filter" value="Axes">
                    <button class="button" type="submit">Axes</button>
                </form>
                <form class="m-0" action="vehiculeList.php" method="post">
                    <input class="recherche" type="text" name="filter" placeholder="Recherche par ID">
                    <button class="button recherche" type="submit">Recherche</button>
                </form>
            </div>
        </header>
        <section class="contentContainer w-100 d-flex flex-column justify-content-between align-items-center">
            <div class="list w-100 d-flex flex-column <?php if(count($results) <= 0) { echo "justify-content-center";}?> align-items-center">
                <?php
                if(count($results) > 0) {
                    foreach($results as $vehicule) {
                        echo '
                        <article class="infoContainer bg-white d-flex align-items-center">
                            <div class="matriculeVehicule borderRight sections d-flex flex-column justify-content-between">
                                <h3>Matricule</h3>
                                <div>
                                    <p>'.$vehicule["matricule"].'</p>
                                    <hr>
                                </div>
                            </div>
                            <div class="vehiculeType borderRight sections d-flex flex-column justify-content-between">
                                <h3>Type de véhicule</h3>
                                <div>
                                    <p>'.$vehicule["type"].'</p>
                                    <hr>
                                </div>
                            </div>
                             <div class="vehiculeModify sections d-flex flex-column justify-content-between align-items-center">
                                <h3>Modifier les informations</h3>
                                <div>
                                    <form method="post" action="vehiculeList.php">
                                        <input type="hidden" name="matricule" value="'.$vehicule["matricule"].'">
                                        <input type="hidden" name="type" value="'.$vehicule["type"].'">
                                        <button type="submit" name="modify" value="true">Modifier</button>
                                    </form>
                                </div>
                            </div>
                        </article>
                        ';
                    }
                } else {
                    echo '
                    <article class="infoContainer notFound bg-white d-flex justify-content-center align-items-center">
                        <p>Aucun véhicule n\'a été trouvé.</p>
                    </article>
                    ';
                }
                ?>
            </div>
            <div class="bg-white ajout w-100 d-flex justify-content-center align-items-center">
                <form class="m-0" action="vehiculeList.php" method="post">
                    <input type="hidden" name="ajout" value="true">
                    <button>Ajouter un véhicule</button>
                </form>
            </div>
            <div class="bg-white pagination w-100 d-flex justify-content-center align-items-center">
                <?php
                    for ($i = 1; $i <= $pagesTotales; $i++) {
                        if($i == $pageCourante) {
                            echo '
                            <form method="post" action="vehiculeList.php">
                                <input type="hidden" value="'.$i.'" name="pageCourante">
                                <button class="currentPage" type="submit">'.$i.'</button>
                            </form>
                        ';
                        } else {
                            echo '
                            <form method="post" action="vehiculeList.php">
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
        if(array_key_exists("ajout", $_POST) && $_POST["ajout"] !== NULL){
            global $errorMessage;
            ?>
            <section id="popUp" class="popUpContainer d-flex justify-content-center align-items-center w-100">
                <form class="d-flex flex-column align-items-center" method="post" action="vehiculeList.php">
                    <img class="closePopUp" src="../../images/close.png" onClick="document.getElementById('popUp').classList.add('none');">
                    <h2 class="w-100 text-center">Ajout de véhicule</h2>
                    <div class="popUpInfo d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-column align-items-center">
                            <h2>Matricule du véhicule</h2>
                            <input type="text" name="matricule" value="<?php if (array_key_exists("matricule", $_POST)) { echo $_POST["matricule"];}?>">
                        </div>
                        <div class="d-flex flex-column align-items-center">
                            <h2>Type de véhicule</h2>
                            <select name="type">
                                <?php
                                if (array_key_exists("type", $_POST)) {
                                    $options = ["Pendulaire", "AutoGire", "Axes"];
                                    echo '<option value="'.$_POST["type"].'">'.$_POST["type"].'</option>';
                                    foreach($options as $option) {
                                        if ($option !== $_POST['type']) {
                                            echo '<option value="'.$option.'">'.$option.'</option>';
                                        }
                                    }
                                } else {
                                    echo '<option value="Pendulaire">Pendulaire</option>';
                                    echo '<option value="AutoGire">AutoGire</option>';
                                    echo '<option value="Axes">Axes</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="ajout">                  
                    <div class="sendToDB d-flex w-100 justify-content-center align-items-center">
                        <button class="sendDB valider d-flex justify-content-center align-items-center" name="toDB" value="Valider">Valider</button>
                        <?php echo '<p>'.$errorMessage.'</p>'; ?>
                    </div>
                </form>
            </section>
            <?php
         } elseif(array_key_exists("modify", $_POST) && $_POST["modify"] !== NULL) {
            global $errorMessage;
            ?>
            <section id="popUp" class="popUpContainer d-flex justify-content-center align-items-center w-100">
                <form class="d-flex flex-column align-items-center" method="post" action="vehiculeList.php">
                    <img class="closePopUp" src="../../images/close.png" onClick="document.getElementById('popUp').classList.add('none');">
                    <h2 class="w-100 text-center">Ajout de véhicule</h2>
                    <div class="popUpInfo d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-column align-items-center">
                            <h2>Matricule du véhicule</h2>
                            <input type="text" name="matricule" value="<?php echo $_POST["matricule"]?>">
                        </div>
                        <div class="d-flex flex-column align-items-center">
                            <h2>Type de véhicule</h2>
                            <select name="type">
                                <?php
                                $options = ["Pendulaire", "AutoGire", "Axes"];
                                echo '<option value="'.$_POST["type"].'">'.$_POST["type"].'</option>';
                                foreach($options as $option) {
                                    if ($option !== $_POST['type']) {
                                        echo '<option value="'.$option.'">'.$option.'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <?php
                    if(array_key_exists("matriculeOriginal", $_POST)) {
                        echo '<input type="hidden" name="matriculeOriginal" value="'.$_POST["matriculeOriginal"].'">';
                    } else {
                        echo '<input type="hidden" name="matriculeOriginal" value="'.$_POST["matricule"].'">';
                    }
                    ?>
                    <input type="hidden" name="modify">                  
                    <div class="sendToDB d-flex w-100 justify-content-center align-items-center">
                        <button class="sendDB valider d-flex justify-content-center align-items-center" name="toDB" value="Valider">Valider</button>
                        <button class="sendDB supprimer d-flex justify-content-center align-items-center" name="toDB" value="Supprimer">Supprimer</button>
                        <?php echo '<p>'.$errorMessage.'</p>'; ?>
                    </div>
                </form>
            </section>
            <?php
         }
        ?>       
    </body>
</htlm>
