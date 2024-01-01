<style>
    .redBorder {
        border: 2px solid red;
    }
    .red {
        color: red;
    }
</style>
<?php
include "database.php";
include "functions.php";
$allVariable = ["nom", "prenom", "email", "telephone", "type", "reserv"];
$options = ["Pendulaire", "Autogire", "Axes"];
calculateDate();
detectError($allVariable);
var_dump($_POST);
if (!array_key_exists("send", $_POST) || count($errors) > 0) { 
    ?>
    <form action="test.php" method="post">
        <label for="nom">Nom</label>
        <input <?php errorClass("nom") ?> id="nom" name="nom" type="text" placeholder="Nom" <?php ancientValue("nom") ?> >

        <label for="nom">Prenom</label>
        <input <?php errorClass("prenom") ?> id="prenom" name="prenom" type="text" placeholder="Prenom" <?php ancientValue("prenom") ?> >
        
        <label for="email">Email</label>
        <input <?php errorClass("email") ?> id="email" name="email" type="text" placeholder="Email" <?php ancientValue("email") ?> >

        <label for="telephone">Téléphone</label>
        <input <?php errorClass("telephone") ?> id="telephone" name="telephone" type="number" placeholder="Numéro de téléphone" <?php ancientValue("telephone") ?> >

        <label for="type">Type</label>
        <select name="type" id="type">
            <?php generateOption($options) ?>
        </select>
        
        <label for="reserv">Date de Réservation</label>
        <input <?php errorClass("reserv") ?> type="date" name="reserv" id="reserv" min="<?php echo "$currentDate" ?>" max="<?php echo "$nextYear" ?>" <?php ancientValue("reserv") ?> >

        <button type="submit" name="send">Envoyer</button>
    </form>
    <?php
    errorMessage();
} else {
    requeteSQL();
}
?>
