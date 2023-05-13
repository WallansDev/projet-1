<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="icon" href="./assets/img/logo.ico" />
    <title>Ajouter d'un casier - Mairie Belvianes-et-Cavirac</title>
</head>

<body>
    <?php require('./include/header.inc.php');
    require('./include/fonctions.inc.php');
    require('./classes/reposoir.class.php');
    require('./classes/reposoirManager.class.php');
    require('./classes/logs.class.php');
    require('./classes/logsManager.class.php');
    verifEntete();

    if (isset($_POST['ajoutReposoir'])) {
        $idReposoir = $_POST['idReposoir'];
        $placeDispo = $_POST['placeDispo'];

        $erreur = False;


        if (!isset($idReposoir) || strlen($idReposoir) < 1) {
            $erreur = True;
            $textError = "Le numéro de la tombe n'est pas bien renseigné.";
        } else if (!is_numeric($idReposoir)) {
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
                $addReposoir = new Reposoir(['IdReposoir' => $idReposoir, 'PlaceDispo' => $placeDispo]);
                $reposoirManager->add($addReposoir);
                $addLogs = new Logs(['IdUtilisateur' => $_SESSION['IdUser'], 'MessageLogs' => "a ajouter un nouveau casier au reposoir (n°$idReposoir)", 'StatusLogs' => "success"]);
                $logsManager->add($addLogs);
                alertBox("success", "L'ajout à été fait.", "reposoir-details.php?id=" . $idReposoir, 1500);
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
                <h1 class="main-title">Ajout d'un casier</h1>
                <br>
                <form method="post" action="" id="formAjoutReposoir" novalidate>

                    <div class="form-row">
                        <div class="col-md-3"></div>
                        <div class="form-control-group col-md-3">
                            <input type="number" pattern="[0-9]{1}" class="form-control" name="idReposoir" id="idReposoir" placeholder="Numéro de casier *" required>
                            <div class="invalid-feedback">
                                Condition : maximum 4 caractères et du nombre 0 à 9.
                            </div><br>
                        </div>

                        <div class="form-control-group col-md-3">
                            <input type="number" pattern="[0-9]{1}" min="1" max="4" class="form-control" name="placeDispo" id="placeDispo" placeholder="Places disponibles *" required>
                            <div class="invalid-feedback">
                                Condition : maximum 1 caractère et du nombre 1 à 4.
                            </div><br>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-5"></div>
                        <div class="form-control-group col-md-3">
                            <i>* Champs obligatoires</i><br><br>
                            <input type="submit" value="Ajouter" class="btn btn-success btn-lg" name="ajoutReposoir">
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
                var form = document.getElementById("formAjoutReposoir")
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