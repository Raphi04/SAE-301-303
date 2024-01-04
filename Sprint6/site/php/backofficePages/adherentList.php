<?php
session_start();
if(!array_key_exists("logged", $_SESSION)) {
    header("location:../admin.php");
    exit();
}else {
    if($_SESSION["logged"] !== "true") {
        header("location:../admin.php");
        exit();
    }
}
    echo "adherent";
?>