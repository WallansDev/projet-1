<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="icon" href="./assets/img/logo.ico" />
    <title>Rechercher une concession - Mairie Belvianes-et-Cavirac</title>
    <script>
        function aff_type(type) {
            if (type == "civile") {
                toggleDisplay("blocCivile", "inline");
                toggleDisplay("blocColumbarium", "none");
            } else if (type == "columbarium") {
                toggleDisplay("blocCivile", "none");
                toggleDisplay("blocColumbarium", "inline");
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


    @$keywords = $_GET["keywords"];
    @$valider = $_GET['valider'];

    if (isset($valider) && !empty(trim($keywords))) {

        $typeConcession = $_GET['typeConcession'];

        $erreur = False;

        if (strlen($typeConcession) < 1) {
            $erreur = True;
            $textError = "Le type de la concession n'est pas renseigner.";
        }
        if (!is_numeric($keywords)) {
            $erreur = True;
            $textError = "La valeur n'est pas valide.";
        }

        if (!$erreur) {
            if ($typeConcession == "civile") {
                $req = $db->prepare("SELECT CONCESSION.IdConcession AS IdConcession, NumeroPlan, IFNULL(PrixConcession, '--') AS PrixConcession, IFNULL(PlaceDispo, '--') AS PlaceDispo, initCap(IFNULL(TailleConcession, '--')) AS TailleConcession, IFNULL(TempsConcession, '--') AS TempsConcession FROM TOMBECIVILE, CONCESSION WHERE TOMBECIVILE.IdConcession = CONCESSION.IdConcession AND NumeroPlan = '$keywords';");
                $req->setFetchMode(PDO::FETCH_ASSOC);
                $req->execute();
                $tab = $req->fetchAll();
                $afficher = "oui";
            } else if ($typeConcession == "columbarium") {
                $req = $db->prepare("SELECT CONCESSION.IdConcession AS IdConcession, IdCasier, IFNULL(PrixConcession, '--') AS PrixConcession, IFNULL(PlaceDispo, '--') AS PlaceDispo, initCap(IFNULL(TailleConcession, '--')) AS TailleConcession, IFNULL(TempsConcession, '--') AS TempsConcession FROM COLUMBARIUM, CONCESSION WHERE COLUMBARIUM.IdConcession = CONCESSION.IdConcession AND IdCasier = '$keywords';");
                $req->setFetchMode(PDO::FETCH_ASSOC);
                $req->execute();
                $tab = $req->fetchAll();
                $afficher = "oui";
            } else {
                alertBox("danger", "La demande n'a pas pu aboutir : " . $textError, NULL, NULL);
            }
        } else {
            alertBox("danger", "La demande n'a pas pu aboutir : " . $textError, NULL, NULL);
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
                        <div class="form-row">
                            <div class="col-md-4"></div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="civile" name="typeConcession" value="civile" checked>
                                <label class=" custom-control-label" for="civile">Tombe civile</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="columbarium" name="typeConcession" value="columbarium">
                                <label class="custom-control-label" for="columbarium">Columbarium</label>
                            </div>
                        </div>
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
                            <?php
                            if ($typeConcession == "civile") {
                                echo '<th>Numéro sur plan</th>';
                            } else {
                                echo '<th>Numéro de casier</th>';
                            }
                            ?>
                            <th>Prix d'achat (F / €)</th>
                            <th>Places restantes</th>
                            <th>Taille</th>
                            <th>Temps d'acquisition</th>
                            <?php for ($i = 0; $i < count($tab); $i++) { ?>
                        </tr>
                        <?php
                                if ($typeConcession == "civile") {
                                    echo "<td><a class='link' href='concession-details.php?id=" . $tab[$i]['IdConcession'] . "'>n°" . $tab[$i]['NumeroPlan'] . " </a></td>";
                                } else {
                                    echo "<td><a class='link' href='concession-details.php?id=" . $tab[$i]['IdConcession'] . "'>n°" . $tab[$i]['IdCasier'] . " </a></td>";
                                }
                        ?>
                        <td><?php echo $tab[$i]["PrixConcession"]; ?></td>
                        <td><?php echo $tab[$i]["PlaceDispo"]; ?></td>
                        <td><?php echo $tab[$i]["TailleConcession"]; ?></td>
                        <td><?php echo $tab[$i]["TempsConcession"]; ?></td>
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
    <script>
        aff_type('civile');
    </script>
</body>

</html>