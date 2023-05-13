<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="icon" href="./assets/img/logo.ico" />
    <title>Modifier plan cimetière - Mairie Belvianes-et-Cavirac</title>
</head>

<body>
    <?php require('./include/header.inc.php');
    require('./include/fonctions.inc.php');

    require('./classes/logs.class.php');
    require('./classes/logsManager.class.php');
    verifEntete();

    if (isset($_POST['updatePlan'])) {
        $plan = $_FILES['plan']['name'];
        $planTmp = $_FILES['plan']['tmp_name'];
        $dest = 'assets/img/plan-cimetiere.png';
        $file_extension = strrchr($plan, ".");

        if (!empty($_FILES)) {
            $extension_autorisees = array('.png', '.jpg', '.jpeg', '.PNG', '.JPG', '.JPEG');
            if (in_array($file_extension, $extension_autorisees)) {
                move_uploaded_file($planTmp, $dest);
                alertBox("success", "Le plan a été changé.", "plan.php", 1500);
                $addLogs = new Logs(['IdUtilisateur' => $_SESSION['IdUser'], 'MessageLogs' => "a modifier le plan du cimetière", 'StatusLogs' => "warn"]);
                $logsManager->add($addLogs);
            }
        } else {
            alertBox("danger", "La demande n'a pas pu être effectuée car : $textError", NULL, NULL);
        }
    }

    if (isset($_SESSION['username'])) {
    ?>
        <div class="container">
            <br>
            <h1 class="main-title text-center">Modification plan du cimetière</h1>
            <br>
            <form method="post" action="" id="formUpdatePlan" enctype="multipart/form-data" novalidate>
                <div class="form-row">
                    <div class="form-control-group col-md-4">
                    </div>

                    <div class="col-md-3">
                        <div class="text-center">
                            <label for="plan">Plan cimetière</label>
                        </div>
                        <input type="file" class="form-control" name="plan" id="plan" accept=".png, .jpg, .jpeg">
                        <div class="invalid-feedback">
                            La ville est de maximum 45 caractères et de minimum 1 caractère.
                        </div><br>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-5">
                    </div>
                    <div class="col-md-3">
                        <input type="submit" value="Ajouter" class="btn btn-success btn-lg" name="updatePlan">
                    </div>
                </div>
            </form>
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
                var form = document.getElementById("formUpdatePlan")
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