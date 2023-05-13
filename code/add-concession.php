<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="icon" href="./assets/img/logo.ico" />
    <title>Ajouter une concession - Mairie Belvianes-et-Cavirac</title>
    <script>
        function aff_type(type) {
            if (type == "civile") {
                document.getElementById('blocCivile').style.display = "inline";
                document.getElementById('blocColumbarium').style.display = "none";
            } else if (type == "columbarium") {
                document.getElementById('blocCivile').style.display = "none";
                document.getElementById('blocColumbarium').style.display = "inline";
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

    require('./classes/tombeCivile.class.php');
    require('./classes/tombeCivileManager.class.php');
    require('./classes/columbarium.class.php');
    require('./classes/columbariumManager.class.php');
    require('./classes/logs.class.php');
    require('./classes/logsManager.class.php');
    verifEntete();

    if (isset($_POST['ajoutConcession'])) {
        $numeroPlan = $_POST['numeroPlan'];
        $idCasier = $_POST['idCasier'];
        $prixConcession = $_POST['prixConcession'];
        $placeDispo = $_POST['placeDispo'];
        $tailleConcession = $_POST['tailleConcession'];
        $tempsConcession = $_POST['tempsConcession'];
        $typeConcession = $_POST['typeConcession'];

        $erreur = False;

        if ($typeConcession == "civile") {
            if (strlen($numeroPlan) <= 0) {
                $erreur = True;
                $textError = "Numéro Plan (vide)";
            }
            if (!is_numeric($numeroPlan)) {
                $erreur = True;
                $errorText = "Le numéro de plan n'est pas valable.";
            }
        }

        if ($typeConcession == "columbarium") {
            if (strlen($idCasier) <= 0) {
                $erreur = True;
                $textError = "Numéro de casier (vide)";
            }
            if (!is_numeric($idCasier)) {
                $erreur = True;
                $errorText = "Le numéro de plan n'est pas valable.";
            }
        }

        if (isset($prixConcession)) {
            if (strlen($prixConcession) == 0) {
                $prixConcession = NULL;
            } else if (!isset($prixConcession) || !is_numeric($prixConcession)) {
                $erreur = True;
            }
        }

        if (!isset($placeDispo) || !is_numeric($placeDispo)) {
            $erreur = True;
            $textError = "Place disponible (vide)";
        }


        if (strlen($tailleConcession) <= 0) {
            $erreur = True;
            $textError = "Taille de la concession (vide)";
        }

        if (strlen($tempsConcession) <= 0) {
            $erreur = True;
            $textError = "Taille de la concession (vide)";
        }

        if (isset($typeConcession)) {
            if ($result = ($type == "civile" || $type == "columbarium") ? 1 : 0) {
                $erreur = True;
                $textError = "Le type de concession n'est pas valide";
            }
        } else {
            $erreur = True;
            $textError = "Type de la concession (vide)";
        }


        if (!$erreur) {
            try {
                $addConcession = new Concession(['PrixConcession' => $prixConcession, 'PlaceDispo' => $placeDispo, 'TailleConcession' => $tailleConcession, 'TempsConcession' => $tempsConcession]);
                $id = $concessionManager->add($addConcession);
                if ($typeConcession == "civile") {
                    $addCivile = new TombeCivile(['IdConcession' => $id, 'NumeroPlan' => $numeroPlan]);
                    $tombeCivileManager->add($addCivile);
                    $addLogs = new Logs(['IdUtilisateur' => $_SESSION['IdUser'], 'MessageLogs' => "a ajouter une nouvelle concession civile (n°$numeroPlan)", 'StatusLogs' => "success"]);
                    $logsManager->add($addLogs);
                    alertBox("success", "L'ajout à été fait.", "concession-details.php?id=" . $id, 1500);
                } else if ($typeConcession == "columbarium") {
                    $addColumbarium = new Columbarium(['IdConcession' => $id, 'IdCasier' => $idCasier]);
                    $columbariumManager->add($addColumbarium);
                    $addLogs = new Logs(['IdUtilisateur' => $_SESSION['IdUser'], 'MessageLogs' => "a ajouter une nouveau columbarium (n°$idCasier)", 'StatusLogs' => "success"]);
                    $logsManager->add($addLogs);
                    alertBox("success", "L'ajout à été fait.", "concession-details.php?id=" . $id, 1500);
                }
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    alertBox("danger", "Ce numéro existe déjà.", "add-concession.php", 1500);
                }
            }
        } else {
            alertBox("danger", "L'ajout n'à pas pu être fait car : $textError", "add-concession.php", 1500);
        }
    }

    if (isset($_SESSION['username'])) {
    ?>
        <div class="container">
            <br>
            <h1 class="main-title text-center">Ajout d'une concession</h1>
            <br>
            <form method="post" action="" id="formAjoutConcession" novalidate>

                <div class="form-row">

                    <div class="text-center form-control-group col-md-12">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="civile" name="typeConcession" value="civile" onchange="aff_type('civile')" checked>
                            <label class="custom-control-label" for="civile">Tombe civile</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="columbarium" name="typeConcession" value="columbarium" onchange="aff_type('columbarium')">
                            <label class="custom-control-label" for="columbarium">Columbarium</label>
                        </div>
                        <div class="invalid-feedback">
                            Le type est obligatoire
                        </div>
                    </div>
                </div>
                <br>
                <div class="form-row">
                    <div class="form-control-group col-md-3">
                    </div>

                    <div id="blocCivile" class="form-control-group col-md-3">
                        <input type="number" pattern="[0-9]" class="form-control" name="numeroPlan" id="numeroPlan" placeholder="Numéro de concession *">
                        <div class="invalid-feedback">
                            Le numéro de concession est de maximum 4 caractères et du nombre 0 à 5.
                        </div><br>
                    </div>


                    <div id="blocColumbarium" class="form-control-group col-md-3">
                        <input type="number" pattern="[0-9]" class="form-control" name="idCasier" id="idCasier" placeholder="Numéro de casier *">
                        <div class="invalid-feedback">
                            Le numéro de concession est de maximum 4 caractères et du nombre 0 à 5.
                        </div><br>
                    </div>

                    <div class="form-control-group col-md-3">
                        <div class="input-group mb-2">
                            <input type="number" pattern="[1-9]{1-11}" class="form-control" name="prixConcession" id="prixConcession" placeholder="Prix d'achat">
                            <div class="input-group-prepend">
                                <div class="input-group-text">€/F</div>
                            </div>
                            <div class="invalid-feedback">
                                Le prix d'achat ne doit pas avoir de lettres et non null.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-control-group col-md-1">
                    </div>

                    <div class="form-control-group col-md-3">
                        <input type="number" pattern="[0-9]{1,1}" max=4 min=1 class="form-control" name="placeDispo" id="placeDispo" placeholder="Places disponibles *" required>
                        <div class="invalid-feedback">
                            Le nombre de places disponibles doit être de 1 à 4.
                        </div>
                    </div>

                    <div class="form-group col-md-3">
                        <select class="form-control" id="tailleConcession" name="tailleConcession">
                            <option value="" selected hidden>-- Choisir taille --</option>
                            <option value="individuelle">Individuelle</option>
                            <option value="collective">Collective</option>
                            <option value="familiale">Familiale</option>
                        </select>
                        <div class="invalid-feedback">
                            La taille de la concession est obligatoire.
                        </div>
                    </div>

                    <div class="form-group col-md-3.5">
                        <select class="form-control" id="tempsConcession" name="tempsConcession">
                            <option selected hidden>-- Choisir temps d'acquisition --</option>
                            <option value="Temporaire (5 ans - 15 ans)">Temporaire (5 ans - 15 ans)</option>
                            <option value="Trentenaire (30 ans)">Trentenaire (30 ans)</option>
                            <option value="Cinquantenaire (50 ans)">Cinquantenaire (50 ans)</option>
                            <option value="Perpetuelle (pas de limite)">Perpetuelle (pas de limite)</option>
                        </select>
                        <div class="invalid-feedback">
                            Le type de la concession est obligatoire.
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-control-group col-md-5">
                    </div>
                    <p><i>* Champs obligatoires</i></p>
                </div>

                <div class="form-row">
                    <div class="form-control-group col-md-5">
                    </div>
                    <div class="form-control-group col-md-3">
                        <input type="submit" value="Ajouter" class="btn btn-success btn-lg" name="ajoutConcession">
                    </div>
                </div>
            </form>
        </div>
    <?php } else {
        echo "<script>location.href='index.php'</script>";
    } ?>

    <script>
        aff_type('civile');
    </script>
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