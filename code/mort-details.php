<?php
session_start();
$id = $_GET["id"];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="icon" href="./assets/img/logo.ico" />
    <title>Informations mort n°<?php echo $id ?> - Mairie Belvianes-et-Cavirac</title>
    <style>
        p {
            line-height: 50%;
        }
    </style>
</head>

<body>
    <?php require('./include/header.inc.php');
    require('./include/fonctions.inc.php');

    require('./classes/concession.class.php');
    require('./classes/concessionManager.class.php');
    require('./classes/tombeCommunale.class.php');
    require('./classes/tombeCommunaleManager.class.php');
    require('./classes/tombeCivile.class.php');
    require('./classes/tombeCivileManager.class.php');
    require('./classes/columbarium.class.php');
    require('./classes/columbariumManager.class.php');
    require('./classes/reposoir.class.php');
    require('./classes/reposoirManager.class.php');
    require('./classes/mort.class.php');
    require('./classes/mortManager.class.php');
    verifEntete();

    $mort = $mortManager->getId($id);
    if (isset($_SESSION['username'])) {
    ?>
        <div class="container">
            <br>
            <div class="text-center">
                <?php
                if ($concessionManager->VerifConcessionByIdMort($id)) {
                    $concession = $concessionManager->getInfoByIdMort($id);
                    $idConcession = $concession->getIdConcession();

                    if ($columbariumManager->VerifColumbariumByIdConcession($idConcession)) {
                        echo "<h3><u>Informations mort n°" . $mort->getIdMort() . "</u><a href='update-mort.php?id=" . $id . "?type=columbarium'><img src='./assets/img/edit.png' class='img-thumbnail' style='border:none;' width='40px'></a></h3>";
                    }
                    if ($tombeCivileManager->VerifCivileByIdConcession($idConcession)) {
                        echo "<h3><u>Informations mort n°" . $mort->getIdMort() . "</u><a href='update-mort.php?id=" . $id . "?type=civile'><img src='./assets/img/edit.png' class='img-thumbnail' style='border:none;' width='40px'></a></h3>";
                    }
                }

                if ($tombeCommunaleManager->VerifCommunaleByIdMort($id)) {
                    echo "<h3><u>Informations mort n°" . $mort->getIdMort() . "</u><a href='update-mort.php?id=" . $id . "?type=communale'><img src='./assets/img/edit.png' class='img-thumbnail' style='border:none;' width='40px'></a></h3>";
                }

                if ($reposoirManager->VerifReposoirByIdMort($id)) {
                    echo "<h3><u>Informations mort n°" . $mort->getIdMort() . "</u><a href='update-mort.php?id=" . $id . "?type=reposoir'><img src='./assets/img/edit.png' class='img-thumbnail' style='border:none;' width='40px'></a></h3>";
                }
                ?>
                <div class="row">
                    <div class="col-md-5">
                    </div>
                    <div class="col-md-2">
                        <img src="./assets/img/mort.png" width="100%">
                    </div>
                </div>
                <br>
                <p style="font-size: 1.3rem;">Nom de famille : <?php echo $mort->getNomMort(); ?></p>
                <p style="font-size: 1.3rem;">Nom de jeune fille : <?php echo $mort->getNomJeuneMort(); ?></p>
                <p style="font-size: 1.3rem;">Prénom : <?php echo $mort->getPrenomMort() ?></p>
                <p style="font-size: 1.3rem;">Sexe : <?php echo $mort->getSexeMort() ?></p>
                <br>
                <table class='table text-center table-dark'>
                    <tr>
                        <th>Date de naissance</th>
                        <th>Date de décès</th>
                        <th>Date des obsèques</th>
                        <?php if ($concessionManager->VerifConcessionByIdMort($id)) {
                            echo "<th>Numéro de concession</th>";
                        }

                        if ($tombeCommunaleManager->VerifCommunaleByIdMort($id)) {
                            echo "<th>Numéro communale</th>";
                        }

                        if ($reposoirManager->VerifReposoirByIdMort($id)) {
                            echo "<th>Numéro de casier</th>";
                        } ?>
                    </tr>
                    <td><?php echo $mort->getDateNaissance(); ?></td>
                    <td><?php echo $mort->getDateMort(); ?></td>
                    <td><?php echo $mort->getDateObseques(); ?></td>

                    <?php if ($concessionManager->VerifConcessionByIdMort($id)) {
                        $concession = $concessionManager->getInfoByIdMort($id);
                        $idConcession = $concession->getIdConcession();

                        if ($columbariumManager->VerifColumbariumByIdConcession($idConcession)) {
                            $columbarium = $columbariumManager->getInfoByIdConcession($idConcession);
                            echo "<td><a class='link' href='concession-details.php?id=" . $columbarium->getIdConcession() . "'>" . $columbarium->getIdCasier() . "</a></td>";
                        }
                        if ($tombeCivileManager->VerifCivileByIdConcession($idConcession)) {
                            $civile = $tombeCivileManager->getInfoByIdConcession($idConcession);
                            echo "<td><a class='link' href='concession-details.php?id=" . $civile->getIdConcession() . "'>" . $civile->getNumeroPlan() . "</a></td>";
                        }
                    }
                    if ($tombeCommunaleManager->VerifCommunaleByIdMort($id)) {
                        $tombeCommunale = $tombeCommunaleManager->getInfoByIdMort($id);
                        echo "<td><a class='link' href='tombecommunale-details.php?id=" . $tombeCommunale->getIdCommunale() . "'>" . $tombeCommunale->getIdCommunale() . "</a></td>";
                    }
                    if ($reposoirManager->VerifReposoirByIdMort($id)) {
                        $reposoir = $reposoirManager->getInfoByIdMort($id);
                        echo "<td><a class='link' href='reposoir-details.php?id=" . $reposoir->getIdReposoir() . "'>" . $reposoir->getIdReposoir() . "</a></td>";
                    } ?>
                </table>
            </div>
        </div>
    <?php } else {
        echo "<script>location.href='index.php'</script>";
    } ?>
</body>

</html>