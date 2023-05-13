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
    include('./classes/concession.class.php');
    include('./classes/concessionManager.class.php');
    include('./classes/columbarium.class.php');
    include('./classes/columbariumManager.class.php');
    include('./classes/tombecivile.class.php');
    include('./classes/tombecivileManager.class.php');
    require('./classes/logs.class.php');
    require('./classes/logsManager.class.php');
    verifEntete();

    if (isset($_SESSION['username'])) {


        $get_list = $_GET['id'];
        $get_list = explode('?', $get_list);
        $id = $get_list[0];
        $type = $get_list[1];

        if (isset($_POST['delete'])) {
            try {
                if ($mortManager->VerifMortByIdConcession($id)) {
                    $morts = $mortManager->getListByIdConcession($id);
                    foreach ($morts as $mort) {
                        $idMort = $mort->getIdMort();
                        $deleteMort = new Mort(['IdMort' => $idMort]);
                        $mortManager->deleteMort($deleteMort);
                    }
                }

                if ($type == "civile") {
                    if ($tombeCivileManager->VerifCivileByIdConcession($id)) {
                        $idTombe = $tombeCivileManager->getInfoByIdConcession($id);
                        $tombeCivile = new TombeCivile(['IdConcession' => $id]);
                        $concession = new Concession(['IdConcession' => $id]);
                        $tombeCivileManager->delete($tombeCivile);
                        $concessionManager->delete($concession);
                        $addLogs = new Logs(['IdUtilisateur' => $_SESSION['IdUser'], 'MessageLogs' => "a supprimé la concession n°" . $idTombe->getNumeroPlan() . " et tout ce qui y est associé", 'StatusLogs' => "danger"]);
                        $logsManager->add($addLogs);
                    }
                } else if ($type == "columbarium") {
                    if ($columbariumManager->VerifColumbariumByIdConcession($id)) {
                        $idColumbarium = $columbariumManager->getInfoByIdConcession($id);
                        $columbarium = new Columbarium(['IdConcession' => $id]);
                        $concession = new Concession(['IdConcession' => $id]);
                        $columbariumManager->delete($columbarium);
                        $concessionManager->delete($concession);
                        $addLogs = new Logs(['IdUtilisateur' => $_SESSION['IdUser'], 'MessageLogs' => "a supprimé la concession n°" . $idColumbarium->getIdCasier() . " et tout ce qui y est associé", 'StatusLogs' => "danger"]);
                        $logsManager->add($addLogs);
                    }
                }
                alertBox("success", "Suppression réussite.", "concession.php", 3000);
            } catch (PDOException $e) {
                alertBox("danger", "La demande n'a pas pu aboutir.", NULL, NULL);
            }
        }
    ?>
        <div class="container">
            <br>
            <div class="text-center">

                <?php if ($columbariumManager->VerifColumbariumByIdConcession($id)) {
                    $columbarium = $columbariumManager->getInfoByIdConcession($id);
                    echo "<h5>Êtes-vous sûr de supprimer le casier n°" . $columbarium->getIdCasier() . "?</h5>";
                } ?>
                <?php if ($tombeCivileManager->VerifCivileByIdConcession($id)) {
                    $tombecivile = $tombeCivileManager->getInfoByIdConcession($id);
                    echo "<h5>Êtes-vous sûr de supprimer la concession n°" . $tombecivile->getNumeroPlan() . "?</h5>";
                } ?>
                <h6 style="color:#c0392b;"><u><i>Cela sera irréversible</i></u></h6>
                <br>
                <form method="post">
                    <button type="submit" class="btn btn-success" name="delete">Supprimer</button>
                    <a href="delete-concession.php"><button type="button" class="btn btn-danger" name="cancel">Annuler</button></a>
                </form>
            </div>
        </div>
    <?php } else {
        echo "<script>location.href='index.php'</script>";
    } ?>
</body>

</html>