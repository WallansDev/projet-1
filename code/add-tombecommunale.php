<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="icon" href="./assets/img/logo.ico" />
    <title>Ajouter une tombe communale - Mairie Belvianes-et-Cavirac</title>
</head>

<body>
    <?php require('./include/header.inc.php');
    require('./include/fonctions.inc.php');
    require('./classes/tombeCommunale.class.php');
    require('./classes/tombeCommunaleManager.class.php');
    require('./classes/logs.class.php');
    require('./classes/logsManager.class.php');
    verifEntete();

    if (isset($_POST['ajoutTombeCommunale'])) {
        $idCommunale = $_POST['idCommunale'];
        $placeDispo = $_POST['placeDispo'];

        $erreur = False;


        if (!isset($idCommunale) || strlen($idCommunale) < 1) {
            $erreur = True;
            $textError = "Le numéro de la tombe n'est pas bien renseigné.";
        } else if (!is_numeric($idCommunale)) {
            $erreur = True;
            $textError = "La valeur rentré pour le numéro de tombe n'est pas valide.";
        }

        if (!isset($placeDispo) || strlen($placeDispo) < 1 || strlen($placeDispo) > 4 || $placeDispo < 1 || $placeDispo > 4) {
            $erreur = True;
            $textError = "Le nombre de place n'est pas bien renseigné.";
        } else if (!is_numeric($placeDispo)) {
            $erreur = True;
            $textError = "La valeur rentré pour le nombre de places disponibles n'est pas valide.";
        }

        if (!$erreur) {
            try {
                $addTombeCommmunale = new TombeCommunale(['IdCommunale' => $idCommunale, 'PlaceDispo' => $placeDispo]);
                $tombeCommunaleManager->add($addTombeCommmunale);
                $addLogs = new Logs(['IdUtilisateur' => $_SESSION['IdUser'], 'MessageLogs' => "a ajouter une nouvelle tombe communale (n°$idCommunale)", 'StatusLogs' => "success"]);
                $logsManager->add($addLogs);
                alertBox("success", "L'ajout à été fait.", "tombecommunale-details.php?id=" . $idCommunale, 1500);
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    alertBox("danger", "Ce numéro existe déjà.", NULL, NULL);
                }
            }
        } else {
            alertBox("danger", "L'ajout n'à pas pu être fait car : $textError", NULL, NULL);
        }
    }

    if (isset($_SESSION['username'])) {
    ?>
        <div class="container">
            <br>
            <div class="text-center">
                <h1 class="main-title">Ajout d'une tombe communale</h1>
                <br>
                <form method="post" action="" id="formAjoutTombeCommunale" novalidate>

                    <div class="form-row">
                        <div class="col-md-3"></div>
                        <div class="form-control-group col-md-3">
                            <input type="number" pattern="[0-9]{1}" class="form-control" name="idCommunale" id="idCommunale" placeholder="Numéro de tombe *" required>
                            <div class="invalid-feedback">
                                Le numéro de concession est de maximum 4 caractères et du nombre 0 à 9.
                            </div><br>
                        </div>

                        <div class="form-control-group col-md-3">
                            <input type="number" pattern="[0-9]{1}" min="1" max="4" class="form-control" name="placeDispo" id="placeDispo" placeholder="Places disponibles *" required>
                            <div class="invalid-feedback">
                                Le numéro de concession est de maximum 4 caractères et du nombre 1 à 4.
                            </div><br>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-5"></div>
                        <div class="form-control-group col-md-3">
                            <i>* Champs obligatoires</i><br><br>
                            <input type="submit" value="Ajouter" class="btn btn-success btn-lg" name="ajoutTombeCommunale">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php } else {
        echo "<script>location.href='index.php'</script>";
    } ?>

    <script>
        (function() {
            "use strict"
            window.addEventListener("load", function() {
                var form = document.getElementById("formAjoutTombeCommunale")
                form.addEventListener("submit", function(event) {
                    if (form.checkValidity() == false) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add("was-validated")
                }, false)
            }, false)
        }())
    </script>
</body>

</html>