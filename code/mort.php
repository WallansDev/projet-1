<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="icon" href="./assets/img/logo.ico" />
    <title>Rechercher un mort - Mairie Belvianes-et-Cavirac</title>
</head>

<body>
    <?php require('./include/header.inc.php');
    require('./include/fonctions.inc.php');

    require('./classes/mort.class.php');
    require('./classes/mortManager.class.php');

    verifEntete();

    @$keywords = $_GET["keywords"];
    @$valider = $_GET['valider'];

    if (isset($valider) && !empty(trim($keywords))) {
        $req = $db->prepare("SELECT IdMort, IFNULL(UPPER(NomMort), '--') AS NomMort, IFNULL(UPPER(NomJeuneMort), '--') AS NomJeuneMort, initCap(IFNULL(PrenomMort, '--')) AS PrenomMort, initCap(IFNULL(SexeMort, '--')) AS SexeMort, IFNULL(DateNaissance, '--') AS DateNaissance, IFNULL(DateMort, '--') AS DateMort, IFNULL(DateObseques, '--') AS DateObseques FROM MORT WHERE IdMort = '$keywords' OR NomMort LIKE '$keywords%' OR NomJeuneMort LIKE '$keywords%' OR PrenomMort LIKE '$keywords%' OR DateNaissance LIKE '$keywords' OR DateMort LIKE '$keywords' OR DateObseques LIKE '$keywords'");
        $req->setFetchMode(PDO::FETCH_ASSOC);
        $req->execute();
        $tab = $req->fetchAll();
        $afficher = "oui";
    }

    if (isset($_SESSION['username'])) {
    ?>

        <div class="container">
            <br><br>
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8">
                    <form class="card card-sm" name="formMortSearch" method="get" action="">
                        <div class="card-body row no-gutters align-items-center">
                            <div class="col-auto">
                                <i class="fas fa-search h4 text-body"></i>
                            </div>
                            <div class="col">
                                <input class="form-control form-control-lg form-control-borderless" type="search" name="keywords" value="<?php echo $keywords ?>" placeholder="Mots-clés">
                            </div>&ensp;
                            <div class="col-auto">
                                <input class="btn btn-lg btn-success" type="submit" name="valider" value="Rechercher">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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
                            <th>Numéro d'indentification</th>
                            <th>Nom de famille</th>
                            <th>Nom de jeune fille</th>
                            <th>Prénom</th>
                            <th>Sexe</th>
                            <th>Date de naissance</th>
                            <th>Date de décès</th>
                            <th>Date obsèques</th>
                            <?php for ($i = 0; $i < count($tab); $i++) { ?>
                        </tr>
                        <td><a class="link" href="mort-details.php?id=<?php echo $tab[$i]["IdMort"]; ?>">n°<?php echo $tab[$i]["IdMort"]; ?></a></td>
                        <td><?php echo $tab[$i]["NomMort"]; ?></td>
                        <td><?php echo $tab[$i]["NomJeuneMort"]; ?></td>
                        <td><?php echo $tab[$i]["PrenomMort"]; ?></td>
                        <td><?php echo $tab[$i]["SexeMort"]; ?></td>
                        <td><?php echo $tab[$i]["DateNaissance"]; ?></td>
                        <td><?php echo $tab[$i]["DateMort"]; ?></td>
                        <td><?php echo $tab[$i]["DateObseques"]; ?></td>
                    <?php } ?>
                    </table>
                </div>
            <?php } ?>
        </div>
    <?php
        if (isset($_POST['search'])) {
            try {
                $inputSearch = $_POST['input-search'];
                $req = "";
            } catch (Exception $e) {
                echo "Il y a eu un problème";
            }
        }
    } else {
        echo "<script>location.href='index.php'</script>";
    }
    ?>
    <script>
        (function() {
            "use strict"
            window.addEventListener("load", function() {
                var form = document.getElementById("formAjoutConcession")
                form.addEventListener("submit", function(event) {
                    if (form.checkValidity() == false) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add("was-validated")
                }, false)
            }, false)
        }())
    </script>
</body>

</html>