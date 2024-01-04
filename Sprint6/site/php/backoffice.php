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
?>
<!DOCTYPE html>
<html lang="fr" class="h-100 w-100">
    <head>
        <!-- Meta -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Raphael CADETE, Hugo BAJOUE, Yanis WONG">
    
        <title>ACF2L - Backoffice</title>

        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <link href="../css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link rel="stylesheet" href="../css/backoffice/menu.css">
        <link rel="icon" type="image/x-icon" href="../images/logo.png">
    </head>
    <body class="w-100 h-100">
        <div class="container-fluid p-0 m-0 h-100">
            <div class="row container-fluid p-0 m-0 w-100 h-100">

                <div class="col-3 container-fluid w-100 h-100" id="container-gauche">
                    <div class=" d-flex justify-content-center align-items-center m-0 text-center h-100 w-100">
                        <img src="../images/logo.png" alt="logo ACF2L">
                    </div>
                </div>

                <div class="col-9 d-flex justify-content-center align-items-center m-0 text-center" id="container-droite">
                    <div class="d-flex">
                        <div class="d-flex flex-column">
                            <a target="_blank" class="text-decoration-none" href="backofficePages/reservationList.php">
                                <div class="blanc col-10 d-flex flex-column bg-white m-5 p-5">
                                    <h3 class="h3bis mb-4">Réservation</h3>
                                    <div class=" d-flex justify-content-center align-items-center">
                                        <img id="imae" src="../images/Google Calendar.png" alt="Calendrier de Réservation">
                                    </div>
                                </div>
                            </a>
                            <a target="_blank" class="text-decoration-none" href="backofficePages/vehiculeList.php">
                                <div class="blanc col-10 d-flex flex-column bg-white m-5 p-5 ">
                                    <h3 class="h3bis mb-4">Véhicule</h3>
                                    <div class=" d-flex justify-content-center align-items-center">
                                        <img class="imagee" src="../images/Airplane Take Off.png" alt="Icone ULM">
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="d-flex flex-column">
                            <a target="_blank" class="text-decoration-none" href="backofficePages/adherentList.php">
                                <div class="blanc col-10 d-flex flex-column bg-white m-5 p-5">
                                    <h3 class="h3bis mb-4">Adhérent</h3>
                                    <div class=" d-flex justify-content-center align-items-center">
                                        <img class="imagee" src="../images/User.png" alt="Icone Adhérent">
                                    </div>
                                </div>
                            </a>
                            <a target="_blank" class="text-decoration-none" href="backofficePages/staffList.php">
                                <div class="blanc col-10 d-flex flex-column bg-white m-5 p-5">
                                    <h3  class="h3bis mb-4">STAFF</h3>
                                    <div class=" d-flex justify-content-center align-items-center">
                                        <img class="imagee" src="../images/Male User.png" alt="Icone STAFF">
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>

