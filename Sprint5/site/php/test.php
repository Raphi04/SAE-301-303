<?php
include "database.php";

$allVariable = ["nom", "prenom"];
$errors = [];

function checkError() {
    if(array_key_exists("send",$_POST)) {
        global $allVariable;
        global $errors;
        foreach($allVariable as $variable) {
            if ($_POST[$variable] == "") {
                array_push($errors, $variable);
            }
        }
    }
}
?>

<form action="test.php" method="post">
    <label for="nom">Nom</label>
    <input id="nom" name="nom" type="text" placeholder="Nom">
    <label for="nom">Prenom</label>
    <input id="prenom" name="prenom" type="text" placeholder="Prenom">
    <button type="submit" name="send">Envoyer</button>
</form>
<?php
    checkError();
    var_dump($errors);
    var_dump($_POST);
    if(count($errors) > 0){
        echo "erreur";
    }
?>