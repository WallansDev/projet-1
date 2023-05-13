<?php
session_start();
$id = $_GET["id"];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="icon" href="./assets/img/logo.ico" />
    <title>Informations concession - Mairie Belvianes-et-Cavirac</title>
</head>

<body>
    <?php require('./include/header.inc.php');
    require('./include/fonctions.inc.php');
    require('./classes/columbarium.class.php');
    require('./classes/columbariumManager.class.php');
    require('./classes/tombecivile.class.php');
    require('./classes/tombecivileManager.class.php');
    require('./classes/concession.class.php');
    require('./classes/concessionManager.class.php');
    require('./classes/mort.class.php');
    require('./classes/mortManager.class.php');
    verifEntete();


    $mort = $mortManager->getListByIdConcession($id);

    foreach ($mort as $morts) {
        $listeMorts .= "<a class='link' href='mort-details.php?id=" . $morts->getIdMort() . "'>" . $morts->getIdMort() . "</a>, ";
    }

    $concession = $concessionManager->getInfoById($id);

    if (isset($_SESSION['username'])) {
    ?>
        <div class="container">
            <br>
            <div class="text-center">
                <?php if ($columbariumManager->VerifColumbariumByIdConcession($id)) {
                    $columbarium = $columbariumManager->getInfoByIdConcession($id);
                    echo "<h3><u>Informations du casier n°" . $columbarium->getIdCasier() . "</u><a href='update-concession.php?id=" . $id . "?type=columbarium'><img src='./assets/img/edit.png' class='img-thumbnail' style='border:none;' width='40px'></a></h3>";
                } ?>
                <?php if ($tombeCivileManager->VerifCivileByIdConcession($id)) {
                    $tombecivile = $tombeCivileManager->getInfoByIdConcession($id);
                    echo "<h3><u>Détails de la concession n°" . $tombecivile->getNumeroPlan() . "</u><a href='update-concession.php?id=" . $id . "?type=civile'><img src='./assets/img/edit.png' class='img-thumbnail' style='border:none;' width='40px'></a></h3>";
                } ?>
                <br>

                <div class="row">
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
                        <?php
                        if ($columbariumManager->VerifColumbariumByIdConcession($id)) {
                            echo '<img src="./assets/img/columbarium.png" class="img-thumbnail" style="border:none;">';
                        }
                        if ($tombeCivileManager->VerifCivileByIdConcession($id)) {
                            echo '<img src="./assets/img/concession.png" class="img-thumbnail" style="border:none;">';
                        }
                        ?>
                    </div>
                </div>
                <br>
                <table class='table text-center table-dark'>
                    <tr>
                        <th>Prix d'achat (F / €)</th>
                        <th>Places restantes</th>
                        <th>Taille</th>
                        <th>Temps d'acquisition</th>
                        <th>N° personnes décédés</th>
                    </tr>
                    <td><?php echo $concession->getPrixConcession(); ?></td>
                    <td><?php echo $concession->getPlaceDispo(); ?></td>
                    <td><?php echo $concession->getTailleConcession(); ?></td>
                    <td><?php echo $concession->getTempsConcession(); ?></td>
                    <td><?php echo "n°" . substr($listeMorts, 0, -2); ?></td>
                </table>
            </div>
        </div>
    <?php } else {
        echo "<script>location.href='index.php'</script>";
    } ?>
</body>

</html>