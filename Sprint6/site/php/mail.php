<html lang="fr">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <style>
            html {
                width: 100%;
            }

            h1, h2, p {
                margin: 0;
            }
            body {
                margin: 0;
            }
            header {
                display: flex;
                justify-content: center;
                align-items: center;
                background-color: #3874CB;
                height: 150px;
            }

            header h1 {
                color: white;
                font-size: 40px;
            }

            section {
                width: 100%;
                padding: 100px 0px;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            section div {
                width: 80%;
                padding: 50px 20px;
                box-shadow: 0px 0px 30px 0px rgba(0, 0, 0, 0.25);
                border-radius: 30px;
                font-size: 25px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }

            section div p {
                margin-bottom: 25px;
            }

            .rappel {
                margin-bottom: 10px;
            }

            footer {
                padding: 75px 0px;
                background-color: #3874CB;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                color: white;
            }

            .footerBold {
                font-weight: bold;
                font-size: 25px;
                margin-bottom: 25px;
            }

            footer p {
                font-size: 20px;
            }

            a {
                color: white;
            }
        </style>
    </body>
    <header>
        <h1>Aéro-Club Frotey-Lès-Lures</h1>
    </header>
    <section>
        <div>
            <p>Votre demande a bien été prise en compte, une secrétaire va la vérifier dans les prochains jours.</p>
            <p>Quand cela sera fait, vous recevrez un autre email.</p>
            <p><strong>Rappel de votre réservation</strong></p>
            <p class="rappel" >Nom : $_POST["nom"]</p>
            <p class="rappel" >Prenom : $_POST["prenom"]</p>
            <p class="rappel" >Email : $_POST["email"]</p>
            <p class="rappel" >Telephone : $_POST["telephone"]</p>
            <p class="rappel" >Type de vol : $_POST["type"]</p>
            <p class="rappel" >Date de réservattion : $_POST["reservation"]</p>
        </div>
    </section>
    <footer>
        <p class="footerBold">© ACF2L Tous droits réservés</p>
        <p>Un projet Universitaire de <a href="https://www.linkedin.com/in/yaniswong04" target="_blank">Yanis WONG</a>, <a href="https://www.linkedin.com/in/raphaelcadete/" target="_blank">Raphael CADETE</a> et <a href="https://www.linkedin.com/in/hugo-bajoue/" target="_blank">Hugo BAJOUE</a></p>
    </footer>
</html>