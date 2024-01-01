<?php
include "database.php";
include "functions.php";
$allVariable = ["nom", "prenom"];
$errors = [];


?>

<form action="test.php" method="post">
    <label for="nom">Nom</label>
    <input id="nom" name="nom" type="text" placeholder="Nom">
    <label for="nom">Prenom</label>
    <input id="prenom" name="prenom" type="text" placeholder="Prenom">
    <button type="submit" name="send">Envoyer</button>
</form>
<?php
    detectError($allVariable);
    var_dump($errors);
    var_dump($_POST);
?>