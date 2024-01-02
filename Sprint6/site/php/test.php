<style>
    .redBorder {
        border: 2px solid red;
    }
    .red {
        color: red;
    }
</style>
<?php
setcookie("cookie", "accepted", time()+300);
setcookie("testCookie2", "test2", time()+300);
include "database.php";
include "functions.php";

$allVariable = ["nom", "prenom", "email", "telephone", "type", "reserv"];
$options = ["Pendulaire", "Autogire", "Axes"];

checkFirstTime();
calculateDate();
detectError($allVariable);
if (!array_key_exists("send", $_POST) || count($errors) > 0) { 
    ?>
    <form action="test.php" method="post">
        <label for="nom">Nom</label>
        <input <?php errorClass("nom") ?> id="nom" name="nom" type="text" placeholder="Nom" <?php ancientValue("nom") ?> <?php cookieData("nom") ?> >

        <label for="nom">Prenom</label>
        <input <?php errorClass("prenom") ?> id="prenom" name="prenom" type="text" placeholder="Prenom" <?php ancientValue("prenom") ?> <?php cookieData("prenom") ?> >
        
        <label for="email">Email</label>
        <input <?php errorClass("email") ?> id="email" name="email" type="text" placeholder="Email" <?php ancientValue("email") ?> <?php cookieData("email") ?> >

        <label for="telephone">Téléphone</label>
        <input <?php errorClass("telephone") ?> id="telephone" name="telephone" type="number" placeholder="Numéro de téléphone" <?php ancientValue("telephone") ?> <?php cookieData("telephone") ?> >

        <label for="type">Type</label>
        <select name="type" id="type">
            <?php generateOption($options) ?>
        </select>
        
        <label for="reserv">Date de Réservation</label>
        <input <?php errorClass("reserv") ?> type="date" name="reserv" id="reserv" min="<?php echo "$currentDate" ?>" max="<?php echo "$nextYear" ?>" <?php ancientValue("reserv") ?> >

        <input type="hidden" name="secondTime">
        <button type="submit" name="send">Envoyer</button>
    </form>
    <?php
    errorMessage();
} else {
    $getEmail = "SELECT email FROM adherent;";
    $results = getInfoDB($getEmail,"");
    $emailsInDB = [];
    foreach ($results as $result) {
        array_push($emailsInDB, $result["email"]);
    }

    if(!in_array($_POST["email"], $emailsInDB)) {
        $setAdherent = "INSERT INTO adherent (email, prenom, nom, telephone) VALUES (:email, :prenom, :nom, :telephone)";
        $parameters = [
            "email" => $_POST["email"],
            "prenom" => $_POST["prenom"],
            "nom" => $_POST["nom"],
            "telephone" => $_POST["telephone"],
        ];
        addToDB($setAdherent, $parameters);
    }

    if(array_key_exists("cookie", $_COOKIE)) {
        if ($_COOKIE["cookie"] == "accepted") {
            setcookie("email", $_POST["email"], time() + (1 * 60 * 60 * 24 * 365));
            setcookie("nom", $_POST["nom"], time() + (1 * 60 * 60 * 24 * 365));
            setcookie("prenom", $_POST["prenom"], time() + (1 * 60 * 60 * 24 * 365));
            setcookie("telephone", $_POST["telephone"], time() + (1 * 60 * 60 * 24 * 365));
        }
    }
    var_dump($_COOKIE);
    header("location: backoffice.php");
    exit();
}
?>

