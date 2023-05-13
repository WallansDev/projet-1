<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="icon" href="./assets/img/logo.ico" />
    <title>Supprimer une concession - Mairie Belvianes-et-Cavirac</title>
    <script>
        function aff_type(type) {
            if (type == "civile") {
                document.getElementById('blocCivile').style.display = "inline";
                document.getElementById('blocColumbarium').style.display = "none";
            } else if (type == "columbarium") {
                document.getElementById('blocCivile').style.display = "none";
                document.getElementById('blocColumbarium').style.display = "inline";
            }
            return true;
        }
    </script>
</head>

<body>
    <?php require('./include/header.inc.php');
    require('./include/fonctions.inc.php');

    require('./classes/concession.class.php');
    require('./classes/concessionManager.class.php');
    require('./classes/columbarium.class.php');
    require('./classes/columbariumManager.class.php');
    require('./classes/tombeCivile.class.php');
    require('./classes/tombeCivileManager.class.php');
    verifEntete();

    if (isset($_SESSION['username'])) {
        if (isset($_POST['deleteInputButton'])) {
            $deleteInputValue = $_POST['deleteInputValue'];
            $typeConcession = $_POST['typeConcession'];

            $erreur = False;

            if (!isset($deleteInputValue) || !is_numeric($deleteInputValue)) {
                $erreur = True;
            }

            if (isset($typeConcession)) {
                if ($result = ($type == "civile" || $type == "columbarium") ? 1 : 0) {
                    $erreur = True;
                    $textError = "Le type de concession n'est pas valide";
                }
            } else {
                $erreur = True;
                $textError = "Type de la concession (vide)";
            }

            if (!$erreur) {
                if ($typeConcession == "civile") {
                    if ($tombeCivileManager->VerifCivileByNumeroPlan($deleteInputValue)) {
                        $tombecivile = $tombeCivileManager->getInfoByNumeroPlan($deleteInputValue);
                        alertBox("success", "Redirection en cours...", "delete-concession-valid.php?id=" . $tombecivile->getIdConcession() . "?" . $typeConcession, 1000);
                    } else {
                        alertBox("danger", "L'identifiant rentré n'existe pas", NULL, NULL);
                    }
                } else if ($typeConcession == "columbarium") {
                    if ($columbariumManager->VerifColumbariumByIdCasier($deleteInputValue)) {
                        $columbarium = $columbariumManager->getInfoByIdCasier($deleteInputValue);
                        alertBox("success", "Redirection en cours...", "delete-concession-valid.php?id=" . $columbarium->getIdConcession() . "?" . $typeConcession, 1000);
                    } else {
                        alertBox("danger", "L'identifiant rentré n'existe pas", NULL, NULL);
                    }
                }
            } else {
                alertBox("danger", "L'identifiant rentré n'est pas valable", NULL, NULL);
            }
        }
    ?>

        <div class="container">
            <br>
            <h1 class="main-title text-center">Supprimer une concession : </h1>
            <br>
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8">

                    <form class="card card-sm" method="post" novalidate>
                        <br>
                        <div class="text-center form-control-group col-md-12">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="civile" name="typeConcession" value="civile" onchange="aff_type('civile')" checked>
                                <label class="custom-control-label" for="civile">Tombe civile</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="columbarium" name="typeConcession" value="columbarium" onchange="aff_type('columbarium')">
                                <label class="custom-control-label" for="columbarium">Columbarium</label>
                            </div>
                            <div class="invalid-feedback">
                                Le type est obligatoire
                            </div>
                        </div>


                        <div class="card-body row no-gutters align-items-center">
                            <div class="col-auto">
                                <i class="fas fa-search h4 text-body"></i>
                            </div>
                            <div class="col">
                                <input pattern="[a-zA-Z0-9\s]" class="form-control form-control-lg form-control-borderless" name="deleteInputValue" type="search" placeholder="Entrer le numéro de la concession à supprimer">
                            </div>&ensp;
                            <div class="col-auto">
                                <button type="submit" name="deleteInputButton" class="btn btn-lg btn-danger">Supprimer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } else {
        echo "<script>location.href='index.php'</script>";
    } ?>
</body>

</html>