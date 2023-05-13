<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="icon" href="./assets/img/logo.ico" />
    <title>Supprimer une concession - Mairie Belvianes-et-Cavirac</title>
</head>

<body>
    <?php
    require('./include/header.inc.php');
    include('./include/fonctions.inc.php');

    include('./classes/mort.class.php');
    include('./classes/mortManager.class.php');

    require('./classes/logs.class.php');
    require('./classes/logsManager.class.php');
    verifEntete();

    if (isset($_SESSION['username'])) {

        $id = $_GET["id"];

        if (isset($_POST['delete'])) {

            if ($mortManager->VerifMort($id)) {
                try {
                    $deleteMort = $mortManager->getId($id);
                    $mortManager->deleteMort($deleteMort);
                    alertBox("success", "Le mort n°$id a bien été supprimé", "mort.php", 3000);
                    $addLogs = new Logs(['IdUtilisateur' => $_SESSION['IdUser'], 'MessageLogs' => "a supprimer le mort n°$id", 'StatusLogs' => "danger"]);
                    $logsManager->add($addLogs);
                } catch (PDOException $e) {
                    alertBox("danger", "La requête n'a pas pu aboutir", "delete-mort.php", 3000);
                }
            } else {
                alertBox("danger", "La requête n'a pas pu aboutir", "delete-mort.php", 3000);
            }
        }
    ?>
        <div class="container">
            <br>
            <div class="text-center">
                <h5>Êtes-vous sûr de supprimer le mort n°<?php echo $id ?> ?</h5>
                <h6 style="color:#c0392b;"><u><i>Cela sera irréversible</i></u></h6>
                <br>
                <br>
                <form method="post">
                    <button type="submit" class="btn btn-success" name="delete">Supprimer</button>
                    <a href="delete-mort.php"><button type="button" class="btn btn-danger" name="cancel">Annuler</button></a>
                </form>
            </div>
        </div>
    <?php } else {
        echo "<script>location.href='index.php'</script>";
    } ?>
</body>

</html>