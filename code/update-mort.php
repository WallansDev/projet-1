<?php
session_start();
$infos = $_GET['id'];
$list_infos = explode('?type=', $infos);
$id = $list_infos[0];
$type = $list_infos[1];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="icon" href="./assets/img/logo.ico" />
    <title>Modifier un mort - Mairie Belvianes-et-Cavirac</title>
    <script>
        function aff_sexe(sexe) {
            if (sexe == "femme") {
                document.getElementById('nomJeuneFille').style.display = "inline";
            } else {
                document.getElementById('nomJeuneFille').style.display = "none";
            }
        }

        function aff_type(type) {
            if (type == "civile") {
                document.getElementById('blocCivile').style.display = "inline";
                document.getElementById('blocColumbarium').style.display = "none";
                document.getElementById('blocCommunale').style.display = "none";
                document.getElementById('blocReposoir').style.display = "none";
            } else if (type == "columbarium") {
                document.getElementById('blocCivile').style.display = "none";
                document.getElementById('blocColumbarium').style.display = "inline";
                document.getElementById('blocCommunale').style.display = "none";
                document.getElementById('blocReposoir').style.display = "none";
            } else if (type == "communale") {
                document.getElementById('blocCivile').style.display = "none";
                document.getElementById('blocColumbarium').style.display = "none";
                document.getElementById('blocCommunale').style.display = "inline";
                document.getElementById('blocReposoir').style.display = "none";
            } else if (type == "reposoir") {
                document.getElementById('blocCivile').style.display = "none";
                document.getElementById('blocColumbarium').style.display = "none";
                document.getElementById('blocCommunale').style.display = "none";
                document.getElementById('blocReposoir').style.display = "inline";
            }
            return true;
        }
    </script>
</head>

<body>
    <?php require('./include/header.inc.php');
    require('./include/fonctions.inc.php');

    require('./classes/mort.class.php');
    require('./classes/mortManager.class.php');
    require('./classes/concession.class.php');
    require('./classes/concessionManager.class.php');
    require('./classes/tombeCivile.class.php');
    require('./classes/tombeCivileManager.class.php');
    require('./classes/columbarium.class.php');
    require('./classes/columbariumManager.class.php');

    require('./classes/logs.class.php');
    require('./classes/logsManager.class.php');
    verifEntete();

    $mort = $mortManager->getId($id);
    if ($concessionManager->VerifConcessionByIdMort($id)) {
        $concession = $concessionManager->getInfoByIdMort($id);
    }

    if (isset($_POST['updateMort'])) {
        $nomMort = $_POST['nomMort'];
        $nomJeuneFille = $_POST['nomJeuneFilleMort'];
        $prenomMort = $_POST['prenomMort'];
        $sexeMort = $_POST['sexeMort'];
        $dateNaissance = $_POST['dateNaissance'];
        $dateMort = $_POST['dateMort'];
        $dateObseques = $_POST['dateObseques'];
        $typeConcession = $_POST['typeConcession'];


        if ($typeConcession == "civile") {
            $idCivile = $_POST['idCivile'];
        } else if ($typeConcession == "columbarium") {
            $idColumbarium = $_POST['idColumbarium'];
        } else if ($typeConcession == "communale") {
            $idCommunale = $_POST['idCommunale'];
        } else if ($typeConcession == "reposoir") {
            $idReposoir = $_POST['idReposoir'];
        }

        $$erreur = False;

        if (isset($nomMort)) {
            if (strlen($nomMort) > 50) {
                $erreur = True;
                $errorText = "Nom de famille non valable";
            } else if (strlen($nomMort) == 0) {
                $nomMort = NULL;
            }
        }
        if (isset($nomJeuneFille)) {
            if (strlen($nomJeuneFille) > 50) {
                $erreur = True;
                $errorText = "Nom de jeune fille non valable";
            } elseif (strlen($nomJeuneFille) == 0) {
                $nomJeuneFille = NULL;
            }
        }
        if (isset($prenomMort)) {
            if (strlen($prenomMort) > 40) {
                $erreur = True;
                $errorText = "Prénom non valable";
            } else if (strlen($prenomMort) == 0) {
                $prenomMort = NULL;
            }
        }
        if (!isset($sexeMort) || strlen($sexeMort) < 2 || strlen($sexeMort) > 5) {
            $erreur = True;
            $errorText = "Sexe non valable";
        }

        if ($typeConcession == "civile") {
            if (!isset($idCivile) || !is_numeric($idCivile)) {
                $erreur = True;
                $errorText = "Numéro de plan non valable";
            }
        } else if ($typeConcession == "columbarium") {
            if (!isset($idColumbarium) || !is_numeric($idColumbarium)) {
                $erreur = True;
                $errorText = "Numéro de casier non valable";
            }
        } else if ($typeConcession == "communale") {
            if (!isset($idCommunale) || !is_numeric($idCommunale)) {
                $erreur = True;
                $errorText = "Numéro de tombe non valable";
            }
        } else if ($typeConcession == "reposoir") {
            if (!isset($idReposoir) || !is_numeric($idReposoir)) {
                $erreur = True;
                $errorText = "Numéro de casier non valable";
            }
        }

        if (strlen($dateNaissance) == 0) {
            if (!validateDate($dateNaissance)) {
                $dateNaissance = NULL;
            }
        }
        if (strlen($dateMort) == 0) {
            if (!validateDate($dateMort)) {
                $dateMort = NULL;
            }
        }
        if (strlen($dateObseques) == 0) {
            if (!validateDate($dateObseques)) {
                $dateObseques = NULL;
            }
        }

        if (!$erreur) {
            try {
                if ($typeConcession == "civile") {
                    $updateMort = new Mort(['NomMort' => $nomMort, 'NomJeuneFille' => $nomJeuneFille, 'PrenomMort' => $prenomMort, 'SexeMort' => $sexeMort, 'DateNaissance' => $dateNaissance, 'DateMort' => $dateMort, 'DateObseques' => $dateObseques, 'IdConcession' => $idCivile, 'IdCommunale' => NULL, 'IdReposoir' => NULL, 'IdMort' => $id]);
                    $mortManager->update($updateMort);
                }
                if ($typeConcession == "columbarium") {
                    $updateMort = new Mort(['NomMort' => $nomMort, 'NomJeuneFille' => $nomJeuneFille, 'PrenomMort' => $prenomMort, 'SexeMort' => $sexeMort, 'DateNaissance' => $dateNaissance, 'DateMort' => $dateMort, 'DateObseques' => $dateObseques, 'IdConcession' => $idColumbarium, 'IdCommunale' => NULL, 'IdReposoir' => NULL, 'IdMort' => $id]);
                    $mortManager->update($updateMort);
                }
                if ($typeConcession == "communale") {
                    $updateMort = new Mort(['NomMort' => $nomMort, 'NomJeuneFille' => $nomJeuneFille, 'PrenomMort' => $prenomMort, 'SexeMort' => $sexeMort, 'DateNaissance' => $dateNaissance, 'DateMort' => $dateMort, 'DateObseques' => $dateObseques, 'IdConcession' => NULL, 'IdCommunale' => $idCommunale, 'IdReposoir' => NULL, 'IdMort' => $id]);
                    $mortManager->update($updateMort);
                }
                if ($typeConcession == "reposoir") {
                    $updateMort = new Mort(['NomMort' => $nomMort, 'NomJeuneFille' => $nomJeuneFille, 'PrenomMort' => $prenomMort, 'SexeMort' => $sexeMort, 'DateNaissance' => $dateNaissance, 'DateMort' => $dateMort, 'DateObseques' => $dateObseques, 'IdConcession' => NULL, 'IdCommunale' => NULL, 'IdReposoir' => $idReposoir, 'IdMort' => $id]);
                    $mortManager->update($updateMort);
                }
                $addLogs = new Logs(['IdUtilisateur' => $_SESSION['IdUser'], 'MessageLogs' => "a modifier le mort n°$id", 'StatusLogs' => "success"]);
                $logsManager->add($addLogs);
                alertBox("success", "Modification effectuée.", "mort-details.php?id=$id", 1500);
            } catch (PDOException $e) {
                if ($e->getCode() == 20000) {
                    alertBox("danger", "La concession est pleine.", NULL, NULL);
                } else if ($e->getCode() == 20001) {
                    alertBox("danger", "Meme id.", NULL, NULL);
                } else {
                    alertBox("danger", "La demande n'a pas pu aboutir.", NULL, NULL);
                }
            }
        } else {
            alertBox("danger", "La demande n'a pas pu être effectuée car : $errorText", NULL, NULL);
        }
    }

    if (isset($_SESSION['username'])) {
    ?>
        <div class="container">
            <br>
            <div class="text-center">
                <h3>Modification du mort n°<?php echo $mort->getIdMort(); ?></h3>
                <br>
                <form method="post" action="" id="formUpdateMort" novalidate>

                    <div class="form-row">
                        <div class="form-control-group col-md-1">
                        </div>

                        <!-- Nom de famille -->
                        <div class="form-control-group col-md-3">
                            <label>Nom de famille</label>
                            <input type="text" pattern="[-a-zA-Z0-9ïîôöêëâäÄÂÊËÎÏÖÔ\s]{1,50}" class="form-control" name="nomMort" id="nomMort" value="<?php echo $mort->getNomMort(); ?>">
                            <div class=" invalid-feedback">
                                Le nom de famille est de maximum 50 caractères.
                            </div><br>
                        </div>

                        <!-- Nom de jeune fille -->
                        <div id="nomJeuneFille" class="form-control-group col-md-3">
                            <label>Nom de jeune fille</label>
                            <input type="text" pattern="[-a-zA-Z0-9ïîôöêëâäÄÂÊËÎÏÖÔ\s]{1,50}" class="form-control" name="nomJeuneFilleMort" id="nomJeuneFilleMort" value="<?php echo $mort->getNomJeuneMort(); ?>">
                            <div class="invalid-feedback">
                                Le prénom est de maximum 50 caractères et de minimum 2 caractères.
                            </div><br>
                        </div>

                        <!-- Prénom -->
                        <div class="form-control-group col-md-3">
                            <label>Prénom</label>
                            <input type="text" pattern="[-a-zA-Z0-9ïîôöêëâäÄÂÊËÎÏÖÔ\s]{2,40}" class="form-control" name="prenomMort" id="prenomMort" value="<?php echo $mort->getPrenomMort(); ?>">
                            <div class="invalid-feedback">
                                Le prénom est de maximum 50 caractères et de minimum 2 caractères.
                            </div><br>
                        </div>
                    </div>
                    <div class="form-row">

                        <div class="form-control-group col-md-1"></div>

                        <!-- Sexe -->
                        <div class="form-control-group col-md-4">
                            <label class="md-3" for="sexeMort">Sexe :</label><br>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="homme" name="sexeMort" value="homme" onchange="aff_sexe('homme')" <?php if ($mort->getSexeMort() == "homme") {
                                                                                                                                                            echo "checked";
                                                                                                                                                        } ?>>
                                <label class="custom-control-label" for="homme">Homme</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="femme" name="sexeMort" value="femme" onchange="aff_sexe('femme')" <?php if ($mort->getSexeMort() == "femme") {
                                                                                                                                                            echo "checked";
                                                                                                                                                        } ?>>
                                <label class="custom-control-label" for="femme">Femme</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="n/a" name="sexeMort" value="n/a" onchange="aff_sexe('na')" <?php if ($mort->getSexeMort() == "n/a") {
                                                                                                                                                    echo "checked";
                                                                                                                                                } ?>>
                                <label class="custom-control-label" for="n/a">Non Renseigner</label>
                            </div>
                            <div class="invalid-feedback">
                                Le type est obligatoire
                            </div>
                        </div>

                        <div class="text-center form-control-group col-md-7">
                            <label class="md-3" for="sexeMort">Lieu d'enterrement :</label><br>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="civile" name="typeConcession" value="civile" onchange="aff_type('civile')" <?php if ($type == "civile") {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?>>
                                <label class="custom-control-label" for="civile">Tombe civile</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="columbarium" name="typeConcession" value="columbarium" onchange="aff_type('columbarium')" <?php if ($type == "columbarium") {
                                                                                                                                                                                    echo "checked";
                                                                                                                                                                                } ?>>
                                <label class="custom-control-label" for="columbarium">Columbarium</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="communale" name="typeConcession" value="communale" onchange="aff_type('communale')" <?php if ($type == "communale") {
                                                                                                                                                                                echo "checked";
                                                                                                                                                                            } ?>>
                                <label class="custom-control-label" for="communale">Tombe Communale</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="reposoir" name="typeConcession" value="reposoir" onchange="aff_type('reposoir')" <?php if ($type == "reposoir") {
                                                                                                                                                                            echo "checked";
                                                                                                                                                                        } ?>>
                                <label class="custom-control-label" for="reposoir">Reposoir</label>
                            </div>
                            <div class="invalid-feedback">
                                Le type est obligatoire
                            </div>
                        </div>

                    </div>
                    <br><br>

                    <div class="form-row">
                        <div class="col-md-3"></div>

                        <div id="blocCivile" class="form-group col-md-5">
                            <label>Choisir une concession</label>
                            <div class="form-group">
                                <select class="form-control" id="idCivile" name="idCivile">
                                    <?php
                                    if ($type == "civile") {
                                        $civile = $tombeCivileManager->getInfoByIdConcession($concession->getIdConcession());
                                        echo '<option value="' . $mort->getIdConcession() . '" selected hidden>' . $civile->getNumeroPlan() . '</option>';
                                    } else {
                                        echo '<option selected hidden>-- Choisir une concession --</option>';
                                    }
                                    $sql = "SELECT IdConcession, NumeroPlan FROM TOMBECIVILE ORDER BY NumeroPlan;";
                                    foreach ($db->query($sql) as $row) {
                                        echo '<option value="' . $row["IdConcession"] . '">' .  $row["NumeroPlan"] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="invalid-feedback">
                                La numéro de la concession est obligatoire.
                            </div>
                        </div>

                        <div id="blocColumbarium" class="form-group col-md-5">
                            <label>Choisir un casier</label>
                            <div class="form-group">
                                <select class="form-control" id="idColumbarium" name="idColumbarium">
                                    <?php
                                    if ($type == "columbarium") {
                                        $columbarium = $columbariumManager->getInfoByIdConcession($concession->getIdConcession());
                                        echo '<option value="' . $mort->getIdConcession() . '" selected hidden>' . $columbarium->getIdCasier() . '</option>';
                                    } else {
                                        echo '<option selected hidden>-- Choisir un casier --</option>';
                                    }
                                    $sql = "SELECT IdConcession, IdCasier FROM COLUMBARIUM ORDER BY IdCasier;";
                                    foreach ($db->query($sql) as $row) {
                                        echo '<option value="' . $row["IdConcession"] . '">' .  $row["IdCasier"] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="invalid-feedback">
                                La numéro de la concession est obligatoire.
                            </div>
                        </div>

                        <div id="blocCommunale" class="form-group col-md-5">
                            <label>Choisir une tombe</label>
                            <div class="form-group">
                                <select class="form-control" id="idCommunale" name="idCommunale">
                                    <?php if ($type == "communale") {
                                        echo '<option value="' . $mort->getIdCommunale() . '" selected hidden>' . $mort->getIdCommunale() . '</option>';
                                    } else {
                                        echo '<option selected hidden>-- Choisir une tombe --</option>';
                                    }
                                    $sql = "SELECT IdCommunale FROM TOMBECOMMUNALE ORDER BY IdCommunale;";
                                    foreach ($db->query($sql) as $row) {
                                        echo '<option value="' . $row["IdCommunale"] . '">' .  $row["IdCommunale"] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="invalid-feedback">
                                La numéro de la concession est obligatoire.
                            </div>
                        </div>

                        <div id="blocReposoir" class="form-group col-md-5">
                            <label>Choisir un casier</label>
                            <div class="form-group">
                                <select class="form-control" id="idReposoir" name="idReposoir">
                                    <?php if ($type == "reposoir") {
                                        echo '<option value="' . $mort->getIdReposoir() . '" selected hidden>' . $mort->getIdReposoir() . '</option>';
                                    } else {
                                        echo '<option selected hidden>-- Choisir un casier --</option>';
                                    }
                                    $sql = "SELECT IdReposoir FROM REPOSOIR ORDER BY IdReposoir;";
                                    foreach ($db->query($sql) as $row) {
                                        echo '<option value="' . $row["IdReposoir"] . '">' .  $row["IdReposoir"] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="invalid-feedback">
                                La numéro de la concession est obligatoire.
                            </div>
                        </div>

                    </div>

                    <div class="form-row">
                        <div class="form-control-group col-md-1">
                        </div>

                        <div class="form-control-group col-md-3">
                            <label for="dateNaissance">Date Naissance : </label>
                            <input type="date" class="form-control" name="dateNaissance" id="dateNaissance">
                            <div class="invalid-feedback">
                                Format : DD/MM/YYYY
                            </div><br>
                        </div>

                        <div class="form-control-group col-md-3">
                            <label for="dateMort">Date Mort : </label>
                            <input type="date" class="form-control" name="dateMort" id="dateMort">
                            <div class="invalid-feedback">
                                Format : DD/MM/YYYY
                            </div><br>
                        </div>


                        <div class="form-control-group col-md-3">
                            <label for="dateObseques">Date Obsèques : </label>
                            <input type="date" class="form-control" name="dateObseques" id="dateObseques">
                            <div class="invalid-feedback">
                                Format : DD/MM/YYYY
                            </div><br>
                        </div>
                    </div>
            </div>

            <div class="form-row">
                <div class="form-control-group col-md-5">
                </div>
                <div class="form-control-group col-md-3">
                    <p><i>* Champs obligatoires</i></p>
                    <input type="submit" value="Modifier" class="btn btn-success btn-lg" name="updateMort">
                </div>
            </div>
            </form>
        </div>
        </div>
    <?php
    } else {
        echo "<script>location.href='index.php'</script>";
    }

    echo "<script>aff_sexe('" . $mort->getSexeMort() . "')</script>";
    echo "<script>aff_type('" . $type . "')</script>";
    ?>
    <script>
        (function() {
            "use strict"
            window.addEventListener("load", function() {
                var form = document.getElementById("formUpdateMort")
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