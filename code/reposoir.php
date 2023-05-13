<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="icon" href="./assets/img/logo.ico" />
    <title>Rechercher un casier du reposoir - Mairie Belvianes-et-Cavirac</title>
</head>

<body>
    <?php require('./include/header.inc.php');
    require('./include/fonctions.inc.php');

    require('./classes/concession.class.php');
    require('./classes/concessionManager.class.php');

    require('./classes/reposoir.class.php');
    require('./classes/reposoirManager.class.php');
    verifEntete();


    @$keywords = $_GET["keywords"];
    @$valider = $_GET['valider'];

    if (isset($valider) && !empty(trim($keywords))) {

        $erreur = False;

        if (!is_numeric($keywords)) {
            $erreur = True;
            $textError = "La valeur n'est pas valide.";
        }

        if (!$erreur) {
            $req = $db->prepare("SELECT IdReposoir, IFNULL(PlaceDispo, '--') AS PlaceDispo FROM REPOSOIR WHERE IdReposoir = '$keywords';");
            $req->setFetchMode(PDO::FETCH_ASSOC);
            $req->execute();
            $tab = $req->fetchAll();
            $afficher = "oui";
        } else {
            alertBox("danger", "Erreur : " . $textError, NULL, NULL);
        }
    }

    if (isset($_SESSION['username'])) {
    ?>
        <div class="container">
            <br><br>
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8">

                    <form class="card card-sm" name="formMortSearch" method="get" action="">
                        <br>
                        <div class="card-body row no-gutters align-items-center">
                            <div class="col-auto">
                                <i class="fas fa-search h4 text-body"></i>
                            </div>
                            <div class="col">
                                <input class="form-control form-control-lg form-control-borderless" type="search" name="keywords" value="<?php echo $keywords ?>" placeholder="Rechercher par mots-clés">
                            </div>&ensp;
                            <div class="col-auto">
                                <input class="btn btn-lg btn-success" type="submit" name="valider" value="Rechercher">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <br>
            <?php
            if (@$afficher == "oui") { ?>
                <div id="row">
                    <br>
                    <div id="nbr"><?php
                                    if (count($tab) == 0) {
                                        alertBox("danger", count($tab) . " résultat trouvé", NULL, NULL);
                                    } else if (count($tab) == 1) {
                                        alertBox("success", count($tab) . " résultat trouvé", NULL, NULL);
                                    } else if (count($tab) > 1) {
                                        alertBox("success", count($tab) . " résultats trouvés", NULL, NULL);
                                    }
                                    ?></div>
                    <table class="table text-center table-dark">
                        <tr>
                            <th>Numéro de casier</th>
                            <th>Places restantes</th>
                            <?php for ($i = 0; $i < count($tab); $i++) { ?>
                        </tr>
                        <?php echo "<td><a class='link' href='reposoir-details.php?id=" . $tab[$i]['IdReposoir'] . "'>n°" . $tab[$i]['IdReposoir'] . " </a></td>"; ?>
                        <td><?php echo $tab[$i]["PlaceDispo"]; ?></td>
                    <?php } ?>
                    </table>
                </div>
            <?php } ?>
        </div>
    <?php
    } else {
        echo "<script>location.href='index.php'</script>";
    }
    ?>
</body>

</html>