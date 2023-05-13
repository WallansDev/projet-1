<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="icon" href="./assets/img/logo.ico" />
    <title>Acceuil - Mairie Belvianes-et-Cavirac</title>
    <style>
        body {
            background-image: url("./assets/img/background.png");
            background-size: cover;
        }

        #main-title {
            margin-top: 25%;
            text-align: center;
            color: white;
            -webkit-text-stroke: 1px black;
        }
    </style>
</head>

<body>
    <?php
    require('./include/header.inc.php');
    require('./include/fonctions.inc.php');
    verifEntete();
    ?>
    <div class="container">
    </div>
</body>

</html>