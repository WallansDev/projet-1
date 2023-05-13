<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="icon" href="./assets/img/logo.ico" />
    <title>Modifier un casier du reposoir - Mairie Belvianes-et-Cavirac</title>
</head>

<body>
    <?php require('./include/header.inc.php');
    require('./include/fonctions.inc.php');

    require('./classes/reposoir.class.php');
    require('./classes/reposoirManager.class.php');
    require('./classes/logs.class.php');
    require('./classes/logsManager.class.php');
    verifEntete();

    $id = $_GET['id'];

    $reposoir = $reposoirManager->getInfoByIdReposoir($id);

    if (isset($_POST['updateReposoir'])) {
        $placeDispo = $_POST['placeDispo'];

        $erreur = False;

        if (!isset($placeDispo) || !is_numeric($placeDispo) || strlen($placeDispo) <= 0) {
            $erreur = True;
            $textError = "Place disponible (vide)";
        }

        if (!$erreur) {
            try {
                $updateReposoir = new Reposoir(['IdReposoir' => $id, 'PlaceDispo' => $placeDispo]);
                $reposoirManager->update($updateReposoir);
                $addLogs = new Logs(['IdUtilisateur' => $_SESSION['IdUser'], 'MessageLogs' => "a modifier un casier (reposoir n°$id)", 'StatusLogs' => "success"]);
                $logsManager->add($addLogs);
                alertBox("success", "Modification effectuée.", "reposoir-details.php?id=$id", 1500);
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    alertBox("danger", "Ce numéro existe déjà.", NULL, NULL);
                }
            }
        } else {
            alertBox("danger", "La demande n'a pas pu être effectuée car : $textError", NULL, NULL);
        }
    }

    if (isset($_SESSION['username'])) {
    ?>
        <div class="container">
            <br>
            <div class="text-center">
                <h3>Modification du casier n°<?php echo $reposoir->getIdReposoir(); ?></h3>
                <br>
                <form method="post" action="" id="formUpdateReposoir" novalidate>
                    <div class="form-row">
                        <div class="form-control-group col-md-5"></div>

                        <div class="form-control-group col-md-3 ">
                            <input type="number" pattern="[0-9]{1}" min="1" max="4" class="form-control" name="placeDispo" id="placeDispo" placeholder="Places disponibles *" required>
                            <div class="invalid-feedback">
                                Le numéro de concession est de maximum 4 caractères et du nombre 1 à 4.
                            </div><br>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-control-group col-md-5">
                        </div>
                        <div class="form-control-group col-md-3">
                            <p><i>* Champs obligatoires</i></p>
                            <input type="submit" value="Ajouter" class="btn btn-success btn-lg" name="updateReposoir">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php
    } else {
        echo "<script>location.href='index.php'</script>";
    }
    ?>
    <script>
        (function() {
            "use strict"
            window.addEventListener("load", function() {
                var form = document.getElementById("formUpdateReposoir")
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