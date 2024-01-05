<?php
include "database.php";
include "functions.php";

$allVariable = ["nom", "prenom", "email", "telephone", "type", "reservation"];
$options = ["Pendulaire", "Autogire", "Axes"];
$sentToDB = "false";

checkFirstTime();
calculateDate();
detectError($allVariable);

if (array_key_exists("send", $_POST) && count($errors) == 0) { 
    global $sentToDB;
    $sentToDB = "true";

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
    $setReservation = "INSERT INTO reservation (email, dateReserv, type, statut) VALUES (:email, :date, :type, 'En attente')";
    $parameters = [
        "email" => $_POST["email"],
        "date" => $_POST["reservation"],
        "type" => $_POST["type"],
    ];
    addToDB($setReservation, $parameters);

    /*$to = $_POST["email"];
    $subject = "Votre demande de réservation va être prise en compte";
    $message = "coucou";
    $from = "acf2l.gustave@gmail.com";
    $headers="MIME-Version: 1.0" . "rn";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "rn".'X-Mailer: PHP/' . phpversion();
    $headers .= 'From: '.$from."rn".'Reply-To: '.$from."rn";
    mail($to, $subject, $message, $headers);
    */
    }
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ACF2L - Réservation</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <link href="../css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link rel="stylesheet" href="../css/slicknav.css">
        <link rel="stylesheet" href="../css/font-awesome.min.css">
        <link href="../css/custom.css" rel="stylesheet" media="screen">
        <link href="../css/reservation.css" rel="stylesheet" media="screen">
        <link rel="icon" type="image/x-icon" href="../images/logo.png">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Bienvenue sur le site officiel de L'ACF2L ! Rejoignez nous dans notre centre de formation aéraunotique de l'aéroclub de Frotey-lès-Lure. Nous proposons de former de futur pilote et instructeur ainsi que des baptème de l'air.">
        <meta name="keywords" content="aéroclub, aviation, club, aéronotique, avion, ACF2L">
        <meta name="author" content="Raphael CADETE, Hugo BAJOUE, Yanis WONG">
    </head>
    <body id="mainContainer" <?php if ($sentToDB == "true") { echo "class='scrollDisable'";} ?> >
        <?php
        if ($sentToDB == "true") {
            echo '
                <div id="popUpContainer" class="d-flex justify-content-center align-items-center"> 
                    <article id="popUp" class="d-flex justify-content-center align-items-center">
                        <p>La demande de réservation a été envoyé et va être traité dans les prochains jours. <br> Un email récapitulatif vous a été envoyé sur votre adresse mail.</p>
                        <div>
                            <img src="../images/close.png" onClick="document.getElementById(\'popUpContainer\').classList.add(\'popUpDisable\'); document.getElementById(\'mainContainer\').classList.remove(\'scrollDisable\')">
                        </div>
                    </article>
                </div>'
                
        ;}

        ?>
        <article class="bottom-bar">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <div class="header-social-link">
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="#"><i class="fa fa-linkedin"></i></a>
                            <a href="#"><i class="fa fa-instagram"></i></a>
                        </div>
                    </div>
                    
                    <div class="col-md-8 text-right">
                        <div class="school-info">
                            <ul>
                                <li><a href="#"><i class="fa fa-envelope"></i> acf2l@gmail.com</a></li>
                                <li><a href="#"><i class="fa fa-phone"></i> +33 01 60 56 60 60</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </article>
        <header class="header">
            <nav class="navbar navbar-expand-lg navbar-light" id="main-navbar">
                <div class="container-fluid">
                    <!-- Navbar Brand start -->
                    <a class="navbar-brand col-md-3 d-flex align-items-center" href="../index.html"><img class="logo" src="../images/logo.png" alt="" /><div class="navbar-toggle" onClick="responsiveMenu()"></div>					</a>
                    <!-- Navbar Brand end -->
                    
                    <ul class="navbar-nav col-md-9" id="main-menu">
                        <li class="nav-item"><a class="nav-link active" href="../index.html#home">ACCUEIL</a></li>
                        <li class="nav-item"><a class="nav-link" href="../index.html#activities">ACTIVITÉS</a></li>
                        <li class="nav-item"><a class="nav-link" href="../index.html#courses">ULM</a></li>
                        <li class="nav-item"><a class="nav-link" href="../index.html#about-us">MATERIEL</a></li>
                        <li class="nav-item"><a class="nav-link" href="../index.html#teachers">EQUIPE</a></li>
                        <li class="nav-item"><a class="nav-link" href="../index.html#events">EVENEMENTS</a></li>
                        <li class="nav-item"><a class="nav-link" href="../index.html#review">TARIFS</a></li>
                        <li id="navContact" class="nav-item" ><a class="nav-link" href="reservation.php">RESERVATION</a></li>
                    </ul>
                    <div id="responsive-menu"></div>
                </div>
            </nav>	
        </header>
        <section class="container-fluid d-flex align-items-center justify-content-center mr-0  ml-0 pr-0 pl-0" id="bigContainer">

            <form action="reservation.php" method="post" id="qcontainer">

                <div class="bg-white "id="bg-blanc">

                    <h3 class="pr-5 pl-5 pt-3" >Formulaire de Contact</h3>

                    <div class="d-flex flex-column">

                        <div class="d-flex align-items-center border-top pr-5 pl-5 pt-4 w-100" id="toto">

                            <div class="d-flex flex-column mr-3 pr-1">
                                <div class="d-flex align-items-center"id="mod1">
                                    <label class="espace" for="prenom">Prénom <span>*</span></label>
                                    <input  class="input w-100 <?php errorClass("prenom") ?>" type="text" name="prenom" id="prenom" placeholder="Prenom" <?php ancientValue("prenom") ?> <?php cookieData("prenom") ?> >
                                </div>
                                <div class="d-flex align-items-center mt-3"id="mod2">
                                    <label class="espace" for="email">Email <span>*</span></label>
                                    <input  class="input w-100 <?php errorClass("email") ?>" type="text" name="email" id="email" placeholder="Email" <?php ancientValue("email") ?> <?php cookieData("email") ?>>
                                </div>
                            </div>


                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center" id="mod3">
                                    <label class="espace" for="nom">Nom <span>*</span></label>
                                    <input  class="input  w-100 <?php errorClass("nom") ?>" type="text" name="nom" id="nom" placeholder="Nom" <?php ancientValue("nom") ?> <?php cookieData("nom") ?>>
                                </div>

                                <div class="d-flex align-items-center mt-3" id="mod4">
                                    <label class="espace" for="telephone">Téléphone <span>*</span></label>
                                    <input  class="input w-100 <?php errorClass("telephone") ?>" type="number" name="telephone" id="telephone" placeholder="Telephone" <?php ancientValue("telephone") ?> <?php cookieData("telephone") ?>>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="d-flex flex-column pr-5 pl-5 pt-4">

                        <div class="d-flex align-items-center pb-4 w-100" id="tata1">

                            <label id="espace1" for="typeDeVol">Type de Vol <span>*</span></label>
                            <select class="input w-100"  name="type" id="typeDeVol">
                                <?php generateOption($options) ?>
                            </select>

                        </div>
                        
                        <div class="d-flex align-items-center "id="tata2">

                            <label id="espace2" for="dateR">Date de Réservation <span>*</span></label>
                            <input class="input w-100 <?php errorClass("reservation") ?>" type="date" name="reservation" id="dateR" min="<?php echo "$currentDate" ?>" max="<?php echo "$nextYear" ?>" <?php ancientValue("reservation") ?> >

                        </div>

                    </div>

                    <div class="d-flex justify-content-center align-items-center p-4">
                        <?php errorMessage(); ?>
                        <input type="hidden" name="secondTime">
                        <input id="button" type="submit" class="text-white" value="Envoyer" name="send">
                    </div>

                </div>
            </form>

        </section>
        <footer class="footer container-fluid ">
            <div class="container-fluid p-0">
                <img class="w-100" id="imagef" src="../images/Group51.png" alt="design">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Footer logo start -->
                        <div class="footer-logo row d-flex justify-content-center align-items-center mb-3">
                            <img src="../images/logo.png" id="logoFooter" alt="Logo"/>
                            <p id="aero" class="mb-0 text-white"> Aéro-Club de Frotey-les-Lure - Association enregistrée n°04674 - - Agrément n° AS70986858</p>
                        </div>
                        <div class="d-flex justify-content-center align-items-center flex-column">
                            <div class="d-flex mb-1 align-items-center">
                                <div class="bg-w d-flex align-items-center justify-content-center mr-3">
                                    <div class="bg-b">
                                        <img src="../images/Location.png" alt="Localisation">
                                    </div>
                                </div>
                                <p id="local" class="mb-0 text-white">62, Avenue de la République, 70200 Lure</p>
                            </div>
                            <div class="d-flex mb-1 align-items-center">
                                <div class="bg-w d-flex align-items-center justify-content-center mr-3">
                                    <div class="bg-b">
                                        <img src="../images/LetterF.png" alt="Mail">
                                    </div>
                                </div>
                                <p id="mailo" class="mb-0 text-white">acf2l@gmail.com</p>
                            </div>
                            <div class="d-flex mb-1 align-items-center">
                                <div class="bg-w d-flex align-items-center justify-content-center mr-3">
                                    <div class="bg-b">
                                        <img src="../images/Phone.png" alt="Téléphone">
                                    </div>
                                </div>
                                <p id="num" class="mb-0 text-white">+33 01 60 56 60 60</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center mb-3">
                            <a href="https://twitter.com/home"><img class="mx-2 my-3" src="../images/TwitterX.png" alt="Twitter"></a>
                            <a href="https://www.instagram.com/"><img class="mx-2 my-3" src="../images/Instagram Circle.png" alt="Instagram"></a>
                            <a href="https://www.linkedin.com/feed/"><img class="mx-2 my-3" src="../images/LinkedIn Circled.png" alt="Linkedin"></a>
                            <a href="https://fr-fr.facebook.com/"><img class="mx-2 my-3" src="../images/FacebookC.png" alt="Facebook"></a>
                        </div>
                        <!-- Footer logo end -->
                        
                        <!-- Footer Copyright start -->
                        <div class="footer-copyright mb-5">
                            <p class="text-sm font-weight-bold">&copy; ACF2L Tous droits réservés</p>
                            <div class="footer-copyright_">
                                <p class="text-sm">Un projet Universitaire de <a href="https://www.linkedin.com/in/yaniswong04" target="_blank" class="fw-bold">Yanis WONG</a>, <a href="https://www.linkedin.com/in/raphaelcadete/" target="_blank">Raphael CADETE</a> et <a href="https://www.linkedin.com/in/hugo-bajoue/" target="_blank">Hugo BAJOUE</a></p>
                                <p class="text-sm d-block"> Images <a href="https://www.pexels.com/fr-fr/" target="_blank">Pexels</a>, <a target="_blank" href="https://icons8.com/icon/101130/share-2--v1">App</a> icon by <a target="_blank" href="https://icons8.com">Icons8</a>
                            </div>
                        </div>
                        <!-- Footer Copyright end -->
                    </div>
                </div>
            </div>
        </footer>
                <!-- Jquery Library File -->
        <script src="../js/jquery-1.12.4.min.js"></script>

        <!-- Bootstrap js file -->
        <script src="../js/bootstrap.min.js"></script>

        <!-- Swiper Carousel js file -->

        <!-- Slick Nav js file -->
        <script src="../js/jquery.slicknav.js"></script>

        <!-- Main Custom js file -->
        <script src="../js/function.js"></script>
        <script src="../js/header.js"></script>
        <script src="../js/cookie.js"></script>
    </body>
</html>


