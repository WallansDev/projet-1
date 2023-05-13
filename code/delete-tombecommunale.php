<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="icon" href="./assets/img/logo.ico" />
    <title>Supprimer une tombe communale - Mairie Belvianes-et-Cavirac</title>
</head>

<body>
    <?php require('./include/header.inc.php');
    require('./include/fonctions.inc.php');
    require('./classes/tombeCommunale.class.php');
    require('./classes/tombeCommunaleManager.class.php');
    verifEntete();

    if (isset($_SESSION['username'])) {
        if (isset($_POST['deleteInputButton'])) {
            $deleteInputValue = $_POST['deleteInputValue'];

            $erreur = False;

            if (!isset($deleteInputValue) || !is_numeric($deleteInputValue)) {
                $erreur = True;
            }

            if (!$erreur) {
                try {
                    if ($tombeCommunaleManager->VerifCommunaleByIdCommunale($deleteInputValue)) {
                        $tombeCommunale = $tombeCommunaleManager->getInfoByIdCommunale($deleteInputValue);
                        alertBox("success", "Redirection en cours...", "delete-tombecommunale-valid.php?id=" . $tombeCommunale->getIdCommunale(), 1000);
                    } else {
                        alertBox("danger", "L'identifiant rentré n'existe pas.", NULL, NULL);
                    }
                } catch (PDOException $e) {
                    alertBox("danger", "La demande n'a pas pu aboutir.", NULL, NULL);
                }
            } else {
                alertBox("danger", "L'identifiant rentré n'est pas valable.", NULL, NULL);
            }
        }
    ?>
        <div class="container">
            <br>
            <h1 class="main-title text-center">Supprimer une tombe communale : </h1>
            <br>
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8">
                    <form class="card card-sm" method="post" novalidate>
                        <div class="card-body row no-gutters align-items-center">
                            <div class="col-auto">
                                <i class="fas fa-search h4 text-body"></i>
                            </div>
                            <div class="col">
                                <input pattern="[a-zA-Z0-9\s]" class="form-control form-control-lg form-control-borderless" name="deleteInputValue" type="search" placeholder="Entrer le numéro de la tombe à supprimer">
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