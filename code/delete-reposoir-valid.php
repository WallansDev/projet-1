<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="icon" href="./assets/img/logo.ico" />
    <title>Supprimer un casier - Mairie Belvianes-et-Cavirac</title>
</head>

<body>
    <?php
    require('./include/header.inc.php');
    include('./include/fonctions.inc.php');

    include('./classes/mort.class.php');
    include('./classes/mortManager.class.php');
    include('./classes/reposoir.class.php');
    include('./classes/reposoirManager.class.php');
    require('./classes/logs.class.php');
    require('./classes/logsManager.class.php');
    verifEntete();

    if (isset($_SESSION['username'])) {

        $id = $_GET["id"];

        if (isset($_POST['delete'])) {
            try {
                if ($mortManager->VerifMortByIdReposoir($id)) {
                    $morts = $mortManager->getListByIdReposoir($id);
                    foreach ($morts as $mort) {
                        $idMort = $mort->getIdMort();
                        $deleteMort = new Mort(['IdMort' => $idMort]);
                        $mortManager->deleteMort($deleteMort);
                    }
                }
                if ($reposoirManager->VerifReposoirByIdReposoir($id)) {
                    $deleteReposoir = new Reposoir(['IdReposoir' => $id]);
                    $reposoirManager->delete($deleteReposoir);
                    $addLogs = new Logs(['IdUtilisateur' => $_SESSION['IdUser'], 'MessageLogs' => "a supprimé le casier n°$id du reposoir et tout ce qui y est associé", 'StatusLogs' => "danger"]);
                    $logsManager->add($addLogs);
                }
                alertBox("success", "Suppression réussite.", "reposoir.php", 1500);
            } catch (PDOException $e) {
                echo "Erreur : " . $e->getMessage();
                alertBox("danger", "La demande n'a pas pu aboutir.", NULL, NULL);
            }
        }
    ?>
        <div class="container">
            <br>
            <div class="text-center">

                <h5>Êtes-vous sûr de supprimer le casier n°<?php echo $id ?></h5>
                <h6 style="color:#c0392b;"><u><i>Cela sera irréversible</i></u></h6>
                <br>
                <form method="post">
                    <button type="submit" class="btn btn-success" name="delete">Supprimer</button>
                    <a href="delete-reposoir.php"><button type="button" class="btn btn-danger" name="cancel">Annuler</button></a>
                </form>
            </div>
        </div>
    <?php } else {
        echo "<script>location.href='index.php'</script>";
    } ?>
</body>

</html>