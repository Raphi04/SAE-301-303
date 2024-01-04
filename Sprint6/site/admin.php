<?php
include "php/database.php";
include "php/functions.php";

$allVariable = ["utilisateur", "mdp"];
$notInDB = "false";
$mdpDB;

detectError($allVariable);

if (array_key_exists("sent", $_POST)) {
    global $mdpDB;

    $getUtilisateur = "SELECT Mdp FROM admin WHERE Utilisateur = :utilisateur;";
    $parameters = [
        "utilisateur" => $_POST["utilisateur"]
    ];
    $result = getInfoDB($getUtilisateur, $parameters);
    if (count($result) > 0) {
        $mdpDB = $result[0]["Mdp"];
    }
    $mdpHashed = hash("sha256", $_POST["mdp"]);
    if($mdpHashed == $mdpDB) {
        session_start();
        $_SESSION["logged"] = "true";
        header("location:php/backoffice.php");
        exit();
    } else {
        $notInDB = "true";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ACF2L - Backoffice</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="css/admin.css" rel="stylesheet" media="screen">
        <link rel="icon" type="image/x-icon" href="../images/logo.png">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Raphael CADETE, Hugo BAJOUE, Yanis WONG">
    </head>
    <body>
        <section class="container-fluid w-100 d-flex flex-column justify-content-center align-items-center">
            <?php
                if ($notInDB == "true") {
                    echo '
                        <article id="errorMessage" class="bg-white d-flex justify-content-center align-items-center">
                            <p class="test-danger">Le nom d’utilisateur ou le mot de passe est incorrect.</p>
                        </article>
                    ';
                }
            ?>
            <article class="bg-white d-flex justify-content-center align-items-center flex-column">
                <h2>Connexion</h2>
                <form method="post" action="admin.php" class="d-flex justify-content-center align-items-center flex-column">
                    <input class="formeInputs <?php if ($notInDB == "true") { echo 'errorBorder';}?>" type="text" placeholder="Nom d'utilisateur" name="utilisateur" id="utilisateur" value=<?php if(!empty($_POST["utilisateur"])) { echo $_POST["utilisateur"];}?>>
                    <input class="formeInputs <?php if ($notInDB == "true") { echo 'errorBorder';}?>" type="password" placeholder="Mot de passe" name="mdp" id="mdp">
                    <button class="formeInputs" type="submit" name="sent">Se connecter</button>
                </form>
            </article>
        </section>
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>