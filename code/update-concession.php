<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="icon" href="./assets/img/logo.ico" />
    <title>Modifier une concession - Mairie Belvianes-et-Cavirac</title>
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
    require('./classes/logs.class.php');
    require('./classes/logsManager.class.php');
    verifEntete();

    $infos = $_GET['id'];
    $list_infos = explode('?type=', $infos);
    $id = $list_infos[0];

    $concession = $concessionManager->getInfoById($id);

    if (isset($_POST['updateConcession'])) {
        $numeroPlan = $_POST['numeroPlan'];
        $idCasier = $_POST['idCasier'];
        $prixConcession = $_POST['prixConcession'];
        $placeDispo = $_POST['placeDispo'];
        $tailleConcession = $_POST['tailleConcession'];
        $tempsConcession = $_POST['tempsConcession'];

        $erreur = False;

        if ($list_infos[1] == "civile") {
            if (strlen($numeroPlan) <= 0) {
                $erreur = True;
                $textError = "Numéro de plan (vide)";
            }
            if (!is_numeric($numeroPlan)) {
                $erreur = True;
                $errorText = "Le numéro de plan n'est pas valable.";
            }
        }

        if ($list_infos[1] == "columbarium") {
            if (strlen($idCasier) <= 0) {
                $erreur = True;
                $textError = "Numéro de casier (vide)";
            }
            if (!is_numeric($idCasier)) {
                $erreur = True;
                $errorText = "Le numéro de casier n'est pas valable.";
            }
        }

        if (isset($prixConcession)) {
            if (strlen($prixConcession) == 0) {
                $prixConcession = NULL;
            } else if (!isset($prixConcession) || !is_numeric($prixConcession)) {
                $erreur = True;
                $errorText = "Le prix n'est pas valable";
            }
        }

        if (!isset($placeDispo) || !is_numeric($placeDispo) || strlen($placeDispo) <= 0) {
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

        if (!$erreur) {
            try {
                $updateConcession = new Concession(['PrixConcession' => $prixConcession, 'PlaceDispo' => $placeDispo, 'TailleConcession' => $tailleConcession, 'TempsConcession' => $tempsConcession, 'IdConcession' => $id]);
                $concessionManager->update($updateConcession);
                if ($list_infos[1] == "civile") {
                    $updateCivile = new TombeCivile(['NumeroPlan' => $numeroPlan, "IdConcession" => $id]);
                    $tombeCivileManager->update($updateCivile);
                    $addLogs = new Logs(['IdUtilisateur' => $_SESSION['IdUser'], 'MessageLogs' => "a modifier une concession (concession n°$numeroPlan)", 'StatusLogs' => "success"]);
                    $logsManager->add($addLogs);
                }
                if ($list_infos[1] == "columbarium") {
                    $updateColumbarium = new Columbarium(['IdCasier' => $idCasier, "IdConcession" => $id]);
                    $columbariumManager->update($updateColumbarium);
                    $addLogs = new Logs(['IdUtilisateur' => $_SESSION['IdUser'], 'MessageLogs' => "a modifier un casier (columbarium n°$idCasier)", 'StatusLogs' => "success"]);
                    $logsManager->add($addLogs);
                }
                alertBox("success", "Modification effectuée.", "concession-details.php?id=$id", 1500);
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    alertBox("danger", "Ce numéro existe déjà.", NULL, NULL);
                }
            }
        } else {
            alertBox("danger", "La demande n'a pas pu être effectuée car : $textError", NULL, NULL);
        }
    }

    if (isset($_SESSION['username'])) {
    ?>
        <div class="container">
            <br>
            <div class="text-center">
                <?php if ($list_infos[1] == "columbarium") {
                    $columbarium = $columbariumManager->getInfoByIdConcession($id);
                    echo "<h3>Modification du casier n°" . $columbarium->getIdCasier() . "</h3>";
                } ?>
                <?php if ($list_infos[1] == "civile") {
                    $tombecivile = $tombeCivileManager->getInfoByIdConcession($id);
                    echo "<h3>Modification de la concession n°" . $tombecivile->getNumeroPlan() . "</h3>";
                } ?>
                <br>
                <form method="post" action="" id="formAjoutConcession" novalidate>

                    <div class="form-row">
                        <div class="form-control-group col-md-3"></div>

                        <?php if ($list_infos[1] == "civile") { ?>
                            <div class="form-control-group col-md-3">
                                <label for="numeroPlan">Numéro Plan</label>
                                <?php echo '<input type="number" pattern="[0-9]" class="form-control" name="numeroPlan" id="numeroPlan" value="' . $tombecivile->getNumeroPlan() . '" >'; ?>
                                <div class="invalid-feedback">
                                    Le numéro de concession est de maximum 4 caractères et du nombre 0 à 5.
                                </div><br>
                            </div>
                        <?php }
                        if ($list_infos[1] == "columbarium") { ?>
                            <div class="form-control-group col-md-3">
                                <label for="IdCasier">Numéro Casier</label>
                                <?php echo '<input type="number" pattern="[0-9]" class="form-control" name="idCasier" id="idCasier" value="' . $columbarium->getIdCasier() . '">'; ?>
                                <div class="invalid-feedback">
                                    Le numéro de concession est de maximum 4 caractères et du nombre 0 à 5.
                                </div><br>
                            </div>
                        <?php } ?>

                        <div class="form-control-group col-md-3">
                            <label for="prixConcession">Prix d'achat (F/€)</label>
                            <div class="input-group mb-2">
                                <input type="number" pattern="[1-9]{1-11}" class="form-control" name="prixConcession" id="prixConcession" value="<?php echo $concession->getPrixConcession() ?>">
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
                        <div class="form-control-group col-md-2">
                        </div>

                        <div class="form-control-group col-md-3">
                            <label for="prixConcession">Places disponibles</label>
                            <input type="number" pattern="[0-9]{1,1}" max=4 min=1 class="form-control" name="placeDispo" id="placeDispo" value="<?php echo $concession->getPlaceDispo() ?>" required>
                            <div class="invalid-feedback">
                                Le nombre de places disponibles doit être de 1 à 4.
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="tailleConcession">Taille de la concession</label>
                            <select class="form-control" id="tailleConcession" name="tailleConcession">
                                <option value="<?php echo $concession->getTailleConcession() ?>" selected hidden><?php echo $concession->getTailleConcession() ?></option>
                                <option value="individuelle">Individuelle</option>
                                <option value="collective">Collective</option>
                                <option value="familiale">Familiale</option>
                            </select>
                            <div class="invalid-feedback">
                                La taille de la concession est obligatoire.
                            </div>
                        </div>

                        <div class="form-group col-md-3.5">
                            <label for="tempsConcession">Temps de la concession</label>
                            <select class="form-control" id="tempsConcession" name="tempsConcession">
                                <option value="<?php echo $concession->getTempsConcession() ?>" selected hidden><?php echo $concession->getTempsConcession() ?></option>
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
                        <div class="form-control-group col-md-3">
                            <p><i>* Champs obligatoires</i></p>
                            <input type="submit" value="Ajouter" class="btn btn-success btn-lg" name="updateConcession">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php
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