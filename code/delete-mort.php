<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="icon" href="./assets/img/logo.ico" />
    <title>Supprimer un mort - Mairie Belvianes-et-Cavirac</title>
</head>

<body>
    <?php require('./include/header.inc.php');
    require('./include/fonctions.inc.php');

    require('./classes/mort.class.php');
    require('./classes/mortManager.class.php');
    verifEntete();

    if (isset($_SESSION['username'])) {
        if (isset($_POST['deleteInputButton'])) {
            $deleteInputValue = $_POST['deleteInputValue'];
            $erreur = False;
            if (!isset($deleteInputValue) || !is_numeric($deleteInputValue)) {
                $erreur = True;
            }
            if (!$erreur) {
                if ($mortManager->VerifMort($deleteInputValue)) {
                    echo '<script>location.href="delete-mort-valid.php?id=' . $deleteInputValue . '"</script>';
                } else {
                    alertBox("danger", "L'identifiant rentré n'existe pas", "delete-mort.php", 1500);
                }
            } else {
                alertBox("danger", "L'identifiant rentré n'est pas valable", "delete-mort.php", 1500);
            }
        }
    ?>

        <div class="container">
            <br>
            <h1 class="main-title text-center">Supprimer un mort : </h1>
            <br>
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8">
                    <form class="card card-sm" method="post" novalidate>
                        <div class="card-body row no-gutters align-items-center">
                            <div class="col-auto">
                                <i class="fas fa-search h4 text-body"></i>
                            </div>
                            <div class="col">
                                <input pattern="[a-zA-Z0-9\s]" class="form-control form-control-lg form-control-borderless" name="deleteInputValue" type="search" placeholder="Entrer le numéro du mort à supprimer">
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