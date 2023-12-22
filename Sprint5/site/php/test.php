<?php
require("acf2l_traitement.php");
require("acf2l_models.php");
require("acf2l_database.php");

$sql = "SELECT * FROM personne";
$requete1 = new Traitement();
$requete1->requete($sql, null);
echo $requete1;

?>