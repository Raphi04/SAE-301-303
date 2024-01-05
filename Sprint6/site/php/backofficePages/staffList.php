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
$popUpSpecial;

if(array_key_exists("modifySpecialite", $_POST) && $_POST["modifySpecialite"] == "modified") {
    $modifySpecialite = "UPDATE pilote SET type = :specialite WHERE identifiant = :identifiant;";
    $parameters = [
        "specialite" => $_POST["specialite"],
        "identifiant" => $_POST["identifiant"]
    ];
    addToDB($modifySpecialite, $parameters);
} 

if(array_key_exists("toDB", $_POST)) {
    if ($_POST["toDB"] == "Supprimer") {
        $supressSQL = "DELETE FROM staff WHERE identifiant = :identifiant";
        $parameters = [
            "identifiant" => $_POST["identifiant"]
        ];
        addToDB($supressSQL, $parameters);
        foreach ($_POST as $key => $value) {
            $_POST[$key] = NULL;
         }

    } else {
        global $popUpSpecial;
        global $errorInDB;
        global $errorMessage;

        //Check if empty
        $attendus = ["prenom", "nom"];
        $empty = [];
        foreach($attendus as $attendu) {
            if($_POST[$attendu] == "") {
                array_push($empty, $attendu);
            }
        }

        //Check errors
        if (count($empty) > 0) {
            $errorInDB = 1;
            $errorMessage = "Les champs doivent être rempli";
        } else {
            $errorInDB = 0;
        }

        if ($errorInDB == 0) {
            if(array_key_exists("more", $_POST) && $_POST["more"] == "ajout") {
                if($_POST["role"] == "Pilote") {
                    if (array_key_exists("piloteIsOk", $_POST)) {
                        $addToDB = "INSERT INTO staff (prenom, nom, role) VALUES (:prenom, :nom, :role)";
                        $parameters = [
                            "prenom" => $_POST["prenom"],
                            "nom" => $_POST["nom"],
                            "role" => $_POST["role"],
                        ];
                        addToDB($addToDB, $parameters);

                        $getID = "SELECT identifiant FROM staff ORDER BY identifiant DESC LIMIT 1";
                        $result = getInfoDB($getID, "");

                        $addToDB = "INSERT INTO pilote (identifiant, type) VALUES (:identifiant, :type)";
                        $parameters = [
                            "identifiant" => $result[0]["identifiant"],
                            "type" => $_POST["specialite"],
                        ];
                        addToDB($addToDB, $parameters);
                        $_POST["more"] = NULL;
                        
                    }else {
                        $errorInDB = 1;
                        $popUpSpecial = 1;
                    }
                } else {    
                    $addToDB = "INSERT INTO staff (prenom, nom, role) VALUES (:prenom, :nom, :role)";
                    $parameters = [
                        "prenom" => $_POST["prenom"],
                        "nom" => $_POST["nom"],
                        "role" => $_POST["role"],
                    ];
                    addToDB($addToDB, $parameters);
                    $_POST["more"] = NULL;
                }
            } else {
                if($_POST["role"] == "Pilote") {
                    if($_POST["role"] !== $_POST["roleOriginal"]) {
                        if($popUpSpecial = 0 || array_key_exists("popUpSpecial", $_POST)) {
                            $modifSQL = "UPDATE staff set nom = :nom, prenom = :prenom, role = :role WHERE identifiant = :identifiant";
                            $parameters = [
                                "nom" => $_POST["nom"],
                                "prenom" => $_POST["prenom"],
                                "role" => $_POST["role"],
                                "identifiant" => $_POST["identifiant"]
                            ];
                            addToDB($modifSQL, $parameters);
        
                            $addToDB = "INSERT INTO pilote (identifiant, type) VALUES (:identifiant, :type)";
                            $parameters = [
                                "identifiant" => $_POST["identifiant"],
                                "type" => $_POST["specialite"],
                            ];
                            addToDB($addToDB, $parameters);
                            $_POST["more"] = NULL;
                        } else {
                            $errorInDB = 1;
                            $popUpSpecial = 1;
                        }
                    } else {
                        $modifSQL = "UPDATE staff set nom = :nom, prenom = :prenom, role = :role WHERE identifiant = :identifiant";
                        $parameters = [
                            "nom" => $_POST["nom"],
                            "prenom" => $_POST["prenom"],
                            "role" => $_POST["role"],
                            "identifiant" => $_POST["identifiant"]
                        ];
                        addToDB($modifSQL, $parameters);
                        foreach ($_POST as $key => $value) {
                            $_POST[$key] = NULL;
                        }
                    }
                } elseif ($_POST["roleOriginal"] == "Pilote") {
                    $modifSQL = "UPDATE staff set nom = :nom, prenom = :prenom, role = :role WHERE identifiant = :identifiant";
                    $parameters = [
                        "nom" => $_POST["nom"],
                        "prenom" => $_POST["prenom"],
                        "role" => $_POST["role"],
                        "identifiant" => $_POST["identifiant"]
                    ];
                    addToDB($modifSQL, $parameters);

                    $deleteSQL = "DELETE FROM pilote WHERE identifiant =".$_POST["identifiant"]."";
                    addToDB($deleteSQL, "");
                    $_POST["more"] = NULL;
                }else {
                    $modifSQL = "UPDATE staff set nom = :nom, prenom = :prenom, role = :role WHERE identifiant = :identifiant";
                    $parameters = [
                        "nom" => $_POST["nom"],
                        "prenom" => $_POST["prenom"],
                        "role" => $_POST["role"],
                        "identifiant" => $_POST["identifiant"]
                    ];
                    addToDB($modifSQL, $parameters);
                    foreach ($_POST as $key => $value) {
                        $_POST[$key] = NULL;
                    }
                }
            }
        }
    }
}


$getStaff;
$pageCourante;
if(array_key_exists("pageCourante", $_POST)) {
    $pageCourante = $_POST["pageCourante"];
} else {
    $pageCourante = 1;
}

$staffParPage = 15;
$allStaff;
$depart = ($pageCourante - 1) * $staffParPage;

if (array_key_exists("filter", $_POST)) {
    global $depart;
    global $staffParPage;
    global $getStaff;
    global $allStaff;

    if ($_POST["filter"] == "Pilote") {
        
        $getStaff = "SELECT * FROM staff WHERE role=\"Pilote\" ORDER BY identifiant ASC LIMIT ".$depart.",".$staffParPage.";";
        $staffSqlDB = "SELECT COUNT(identifiant) FROM staff";
        $results = getInfoDB($staffSqlDB,"");
        $allStaff = $results[0]["COUNT(identifiant)"];
        
    }elseif ($_POST["filter"] == "Secretaire"){
        
        $getStaff = "SELECT * FROM staff WHERE role=\"Secrétaire\" ORDER BY identifiant ASC LIMIT ".$depart.",".$staffParPage.";";
        $staffSqlDB = "SELECT COUNT(identifiant) FROM staff";
        $results = getInfoDB($staffSqlDB,"");
        $allStaff = $results[0]["COUNT(identifiant)"];
        
    }elseif ($_POST["filter"] == "Meteorologiste"){
        
        $getStaff = "SELECT * FROM staff WHERE role=\"Météorologiste\" ORDER BY identifiant ASC LIMIT ".$depart.",".$staffParPage.";";
        $staffSqlDB = "SELECT COUNT(identifiant) FROM staff";
        $results = getInfoDB($staffSqlDB,"");
        $allStaff = $results[0]["COUNT(identifiant)"];
        
    }elseif ($_POST["filter"] == ""){
        
        $getStaff = "SELECT * FROM staff ORDER BY identifiant ASC LIMIT ".$depart.",".$staffParPage.";";
        $staffSqlDB = "SELECT COUNT(identifiant) FROM staff";
        $results = getInfoDB($staffSqlDB,"");
        $allStaff = $results[0]["COUNT(identifiant)"];
        
    }else {
    
        $getStaff = "SELECT * FROM staff WHERE identifiant=\"".$_POST["filter"]."\" ORDER BY identifiant ASC LIMIT ".$depart.",".$staffParPage.";";
        $staffSqlDB = "SELECT COUNT(identifiant) FROM staff WHERE identifiant=\"".$_POST["filter"]."\"";
        $results = getInfoDB($staffSqlDB,"");
        $allStaff = $results[0]["COUNT(identifiant)"];

    }

} else {

    global $depart;
    global $staffParPage;
    global $allStaff;
    global $getStaff;
    $getStaff = "SELECT * FROM staff ORDER BY identifiant ASC LIMIT ".$depart.",".$staffParPage.";";
    $staffSqlDB = "SELECT COUNT(identifiant) FROM staff";
    $results = getInfoDB($staffSqlDB,"");
    $allStaff = $results[0]["COUNT(identifiant)"];

}

$pagesTotales = ceil($allStaff/$staffParPage); 
$results = getInfoDB($getStaff, "");
?>
<!DOCTYPE html>
<html lang="fr" class="h-100 w-100">
    <head>
        <!-- Meta -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Raphael CADETE, Hugo BAJOUE, Yanis WONG">
    
        <title>ACF2L - Backoffice - Staff</title>

        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <link href="../../css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link rel="stylesheet" href="../../css/backoffice/basics.css">
        <link rel="stylesheet" href="../../css/backoffice/staff.css">
        <link rel="icon" type="../../image/x-icon" href="../images/logo.png">
    </head>
    <body class="w-100 h-100">
        <header class="w-100 bg-white d-flex justify-content-between align-items-center">
            <h1>Staff</h1>
            <div class="buttonContainer d-flex align-items-center">
                <form class="m-0" action="staffList.php" method="post">
                    <input type="hidden" name="filter" value="Pilote">
                    <button class="button" type="submit">Pilote</button>
                </form>
                <form class="m-0" action="staffList.php" method="post">
                    <input type="hidden" name="filter" value="Secretaire">
                    <button class="button" type="submit">Secrétaire</button>
                </form>
                <form class="m-0" action="staffList.php" method="post">
                    <input type="hidden" name="filter" value="Meteorologiste">
                    <button class="button" type="submit">Météorologiste</button>
                </form>
                <form class="m-0" action="staffList.php" method="post">
                    <input class="recherche" type="text" name="filter" placeholder="Recherche par ID">
                    <button class="button recherche" type="submit">Recherche</button>
                </form>
            </div>
        </header>
        <section class="contentContainer w-100 d-flex flex-column justify-content-between align-items-center">
            <div class="list w-100 d-flex flex-column <?php if(count($results) <= 0) { echo "justify-content-center";}?> align-items-center">
                <?php
                if(count($results) > 0) {
                    foreach($results as $staff) {
                        echo '
                        <article class="infoContainer bg-white d-flex align-items-center">
                            <div class="identifiantStaff borderRight sections d-flex flex-column justify-content-between">
                                <h3>Identifiant</h3>
                                <div>
                                    <p>'.$staff["identifiant"].'</p>
                                    <hr>
                                </div>
                            </div>
                            <div class="staffPrenom borderRight sections d-flex flex-column justify-content-between">
                                <h3>Prenom</h3>
                                <div>
                                    <p>'.$staff["prenom"].'</p>
                                    <hr>
                                </div>
                            </div>
                            <div class="staffNom borderRight sections d-flex flex-column justify-content-between">
                                <h3>Nom</h3>
                                <div>
                                    <p>'.$staff["nom"].'</p>
                                    <hr>
                                </div>
                            </div>
                            <div class="staffRole borderRight sections d-flex flex-column justify-content-between">
                                <h3>Rôle</h3>
                                <div>
                                    <p>'.$staff["role"].'</p>
                                    <hr>
                                </div>
                            </div>
                             <div class="staffModify sections d-flex flex-column justify-content-between align-items-center">
                                <h3>Modifier les informations</h3>
                                <div>
                                    <form method="post" action="staffList.php">
                                        <input type="hidden" name="identifiant" value="'.$staff["identifiant"].'">
                                        <input type="hidden" name="prenom" value="'.$staff["prenom"].'">
                                        <input type="hidden" name="nom" value="'.$staff["nom"].'">
                                        <input type="hidden" name="role" value="'.$staff["role"].'">
                                        <input type="hidden" name="more">';
                                        if ($staff["role"] == "Pilote") {
                                            $getSpecialite = "SELECT type FROM pilote WHERE identifiant=\"".$staff["identifiant"]."\";";
                                            $result = getInfoDB($getSpecialite, "");
                                            echo '<input type="hidden" name="specialite" value="'.$result[0]["type"].'">';
                                            echo '<button type="submit" name="modify" value="general">Modifier</button>
                                                  <button type="submit" name="modifySpecialite">Specialité</button>      
                                            ';
                                        } else {
                                            echo '<button type="submit" name="modify">Modifier</button>';
                                        }
                                        echo '
                                    </form>
                                </div>
                            </div>
                        </article>
                        ';
                    }
                } else {
                    echo '
                    <article class="infoContainer notFound bg-white d-flex justify-content-center align-items-center">
                        <p>Aucun(e) membre du staff n\'a été trouvé(e).</p>
                    </article>
                    ';
                }
                ?>
            </div>
            <div class="bg-white ajout w-100 d-flex justify-content-center align-items-center">
                <form class="m-0" action="staffList.php" method="post">
                    <input type="hidden" name="more" value="ajout">
                    <button>Ajouter un employé</button>
                </form>
            </div>
            <div class="bg-white pagination w-100 d-flex justify-content-center align-items-center">
                <?php
                    for ($i = 1; $i <= $pagesTotales; $i++) {
                        if($i == $pageCourante) {
                            echo '
                            <form method="post" action="staffList.php">
                                <input type="hidden" value="'.$i.'" name="pageCourante">
                                <button class="currentPage" type="submit">'.$i.'</button>
                            </form>
                        ';
                        } else {
                            echo '
                            <form method="post" action="staffList.php">
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
        if(array_key_exists("more", $_POST) && $_POST["more"] !== NULL) {
            global $errorInDB;
            global $errorMessage;
            global $popUpSpecial;
            if($popUpSpecial == 1) {
                ?>
                <section id="popUp" class="popUpContainer d-flex justify-content-center align-items-center w-100">
                    <form class="d-flex flex-column align-items-center" method="post" action="staffList.php">
                        <img class="closePopUp" src="../../images/close.png" onClick="document.getElementById('popUp').classList.add('none');">
                        <h2 class="w-100 text-center">Membre du staff <?php if (array_key_exists("modify",$_POST)) { echo ": ".$_POST["prenom"]; echo " ".$_POST["nom"]; }?></h2>
                        <div class="popUpInfo m-0 d-flex justify-content-between align-items-center">
                            <div class="d-flex solo flex-column align-items-center">
                                <h2>Specialité</h2>
                                <select name="specialite">
                                    <option value="Pendulaire">Pendulaire</option>
                                    <option value="AutoGire">AutoGire</option>
                                    <option value="Axes">Axes</option>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="role" value="<?php echo $_POST["role"]?>">
                        <input type="hidden" name="prenom" value="<?php echo $_POST["prenom"]?>">
                        <input type="hidden" name="nom" value="<?php echo $_POST["nom"]?>">
                        <input type="hidden" name="popUpSpecial" value="0">                        
                        <?php
                        if (array_key_exists("identifiant", $_POST)) {
                            echo '<input type="hidden" name="identifiant" value="'.$_POST["identifiant"].'">';
                            echo '<input type="hidden" name="roleOriginal" value="'.$_POST["roleOriginal"].'">';                            echo '<input type="hidden" name="roleOriginal" value="'.$_POST["roleOriginal"].'">';
                        } else {
                            echo '<input type="hidden" name="more" value="ajout">';
                            echo '<input type="hidden" name="piloteIsOk">';
                        }
                        ?>
                        <div class="sendToDB d-flex w-100 justify-content-center align-items-center">
                            <button class="sendDB valider d-flex justify-content-center align-items-center" name="toDB" value="Valider">Valider</button>
                        </div>
                    </form>
                </section>
                <?php
            } elseif (array_key_exists("modifySpecialite", $_POST)){
                ?>
                <section id="popUp" class="popUpContainer d-flex justify-content-center align-items-center w-100">
                    <form class="d-flex flex-column align-items-center" method="post" action="staffList.php">
                        <img class="closePopUp" src="../../images/close.png" onClick="document.getElementById('popUp').classList.add('none');">
                        <h2 class="w-100 text-center">Membre du staff <?php if (array_key_exists("modify",$_POST)) { echo ": ".$_POST["prenom"]; echo " ".$_POST["nom"]; }?></h2>
                        <div class="popUpInfo m-0 d-flex justify-content-between align-items-center">
                            <div class="d-flex solo flex-column align-items-center">
                                <h2>Specialité</h2>
                                <select name="specialite">
                                    <?php
                                    $options = ["Pendulaire", "AutoGire", "Axes"];
                                    echo '<option value="'.$_POST["specialite"].'">'.$_POST["specialite"].'</option>';
                                    foreach($options as $option) {
                                        if ($option !== $_POST['specialite']) {
                                            echo '<option value="'.$option.'">'.$option.'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="modifySpecialite" value="modified">
                        <input type="hidden" name="identifiant" value="<?php echo $_POST["identifiant"] ?>">
                        <div class="sendToDB d-flex w-100 justify-content-center align-items-center">
                            <button class="sendDB valider d-flex justify-content-center align-items-center">Valider</button>
                        </div>
                    </form>
                </section>
                <?php
            } else {
                ?>
                <section id="popUp" class="popUpContainer d-flex justify-content-center align-items-center w-100">
                    <form class="d-flex flex-column align-items-center" method="post" action="staffList.php">
                        <img class="closePopUp" src="../../images/close.png" onClick="document.getElementById('popUp').classList.add('none');">
                        <h2 class="w-100 text-center">Membre du staff <?php if (array_key_exists("modify",$_POST)) { echo ": ".$_POST["prenom"]; echo " ".$_POST["nom"]; }?></h2>
                        <div class="popUpInfo d-flex justify-content-between align-items-center">
                            <div class="d-flex flex-column align-items-center">
                                <h2>Prenom du membre</h2>
                                <input type="text" name="prenom" value="<?php if (array_key_exists("modify",$_POST)) {echo $_POST["prenom"];}?>">
                            </div>
                            <div class="d-flex flex-column align-items-center">
                                <h2>Nom du membre</h2>
                                <input type="text" name="nom" value="<?php if (array_key_exists("modify",$_POST)) {echo $_POST["nom"];}?>">
                            </div>
                        </div>
                        <div class="popUpInfo m-0 d-flex justify-content-between align-items-center">
                            <div class="d-flex solo flex-column align-items-center">
                                <h2>Rôle</h2>
                                <select name="role">
                                    <?php
                                    $options = ["Pilote", "Secretaire", "Meteorologiste"];
                                    if (array_key_exists("role", $_POST)) {
                                        echo '<option value="'.$_POST["role"].'">'.$_POST["role"].'</option>';
                                        foreach($options as $option) {
                                            if ($option !== $_POST['role']) {
                                                echo '<option value="'.$option.'">'.$option.'</option>';
                                            }
                                        }
                                    } else {
                                        echo '<option value="Pilote">Pilote</option>';
                                        echo '<option value="Secretaire">Secrétaire</option>';
                                        echo '<option value="Meteorologiste">Météorologiste</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="sendToDB d-flex w-100 justify-content-center align-items-center">
                            <?php
                            if (array_key_exists("modify", $_POST)) {
                                if(array_key_exists("roleOriginal", $_POST)) {
                                    echo '<input type="hidden" name="roleOriginal" value="'.$_POST["roleOriginal"].'">';
                                } else {
                                    echo '<input type="hidden" name="roleOriginal" value="'.$_POST["role"].'">';
                                }
                            }
                            if(array_key_exists("identifiant", $_POST)) {
                                echo '<input type="hidden" name="identifiant" value="'.$_POST["identifiant"].'">';
                            }
                            ?>
                            <input type="hidden" name="more">
                            <input type="hidden" name="modify" value="general">
                            <?php
                            if(array_key_exists("more", $_POST) && $_POST["more"] == "ajout") {
                                echo '<input type="hidden" name="more" value="ajout">';
                                echo '<button class="sendDB valider d-flex justify-content-center align-items-center" name="toDB" value="Valider">Valider</button>';
                            } else {
                                echo '<button class="sendDB valider d-flex justify-content-center align-items-center" name="toDB" value="Valider">Valider</button>';
                                echo '<button class="supprimer sendDB d-flex justify-content-center align-items-center" name="toDB" value="Supprimer">Supprimer</button>';
                            }
                            if ($errorInDB == 1) {
                                echo '<input type="hidden" name="more">';
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
         }
        ?>       
    </body>
</htlm>
