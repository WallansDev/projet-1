<?php
session_start();
$id = $_GET["id"];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="icon" href="./assets/img/logo.ico" />
    <title>Informations casier n°<?php echo $id ?> - Mairie Belvianes-et-Cavirac</title>
</head>

<body>
    <?php require('./include/header.inc.php');
    require('./include/fonctions.inc.php');
    require('./classes/reposoir.class.php');
    require('./classes/reposoirManager.class.php');
    require('./classes/mort.class.php');
    require('./classes/mortManager.class.php');
    verifEntete();

    $reposoir = $reposoirManager->getInfoByIdReposoir($id);

    $mort = $mortManager->getListByIdReposoir($id);

    foreach ($mort as $morts) {
        $listeMorts .= "<a class='link' href='mort-details.php?id=" . $morts->getIdMort() . "'>" . $morts->getIdMort() . "</a>, ";
    }
    if (isset($_SESSION['username'])) {
    ?>
        <div class="container">
            <br>
            <div class="text-center">
                <h3><u>Informations casier n°<?php echo $reposoir->getIdReposoir() . "</u><a href='update-reposoir.php?id=" . $id . "'><img src='./assets/img/edit.png' class='img-thumbnail' style='border:none;' width='40px'></a></h3>" ?>
                        <br>
                        <div class="row">
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-4">
                                <img src="./assets/img/reposoir.png" width="100%">
                            </div>
                        </div>
                        <br>
                        <table class='table text-center table-dark'>
                            <tr>
                                <th>Places restantes</th>
                                <th>N° personnes décédées</th>
                            </tr>
                            <td><?php echo $reposoir->getPlaceDispo(); ?></td>
                            <td><?php echo "n°" . substr($listeMorts, 0, -2); ?></td>
                        </table>
            </div>
        </div>
    <?php } else {
        echo "<script>location.href='index.php'</script>";
    } ?>
</body>

</html>