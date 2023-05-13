<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="icon" href="./assets/img/logo.ico" />
    <title>Ajouter un mort - Mairie Belvianes-et-Cavirac</title>
    <script>
        function aff_sexe(action) {
            if (action == "homme" || action == "n/a") {
                document.getElementById('nomJeuneFille').style.display = "none";
            } else if (action == "femme") {
                document.getElementById('nomJeuneFille').style.display = "inline-block";
            }
            return true;
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
    <?php
    require('./include/header.inc.php');
    require('./include/fonctions.inc.php');
    require('./include/bdd.inc.php');

    require('./classes/mort.class.php');
    require('./classes/mortManager.class.php');

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

    require('./classes/logs.class.php');
    require('./classes/logsManager.class.php');
    verifEntete();

    if (isset($_POST['ajoutMort'])) {
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
                    $addMort = new Mort(['NomMort' => $nomMort, 'NomJeuneMort' => $nomJeuneFille, 'PrenomMort' => $prenomMort, 'SexeMort' => $sexeMort, 'DateNaissance' => $dateNaissance, 'DateMort' => $dateMort, 'DateObseques' => $dateObseques, 'IdConcession' => $idCivile, 'IdCommunale' => NULL, 'IdReposoir' => NULL]);
                    $id = $mortManager->add($addMort);
                    $addLogs = new Logs(['IdUtilisateur' => $_SESSION['IdUser'], 'MessageLogs' => "a ajouter un nouveau mort (n°$id)", 'StatusLogs' => "success"]);
                    $logsManager->add($addLogs);
                } else if ($typeConcession == "columbarium") {
                    $addMort = new Mort(['NomMort' => $nomMort, 'NomJeuneMort' => $nomJeuneFille, 'PrenomMort' => $prenomMort, 'SexeMort' => $sexeMort, 'DateNaissance' => $dateNaissance, 'DateMort' => $dateMort, 'DateObseques' => $dateObseques, 'IdConcession' => $idColumbarium, 'IdCommunale' => NULL, 'IdReposoir' => NULL]);
                    $id = $mortManager->add($addMort);
                    $addLogs = new Logs(['IdUtilisateur' => $_SESSION['IdUser'], 'MessageLogs' => "a ajouter un nouveau mort (n°$id)", 'StatusLogs' => "success"]);
                    $logsManager->add($addLogs);
                } else if ($typeConcession == "communale") {
                    $addMort = new Mort(['NomMort' => $nomMort, 'NomJeuneMort' => $nomJeuneFille, 'PrenomMort' => $prenomMort, 'SexeMort' => $sexeMort, 'DateNaissance' => $dateNaissance, 'DateMort' => $dateMort, 'DateObseques' => $dateObseques, 'IdConcession' => NULL, 'IdCommunale' => $idCommunale, 'IdReposoir' => NULL]);
                    $id = $mortManager->add($addMort);
                    $addLogs = new Logs(['IdUtilisateur' => $_SESSION['IdUser'], 'MessageLogs' => "a ajouter un nouveau mort (n°$id)", 'StatusLogs' => "success"]);
                    $logsManager->add($addLogs);
                } else if ($typeConcession == "reposoir") {
                    $addMort = new Mort(['NomMort' => $nomMort, 'NomJeuneMort' => $nomJeuneFille, 'PrenomMort' => $prenomMort, 'SexeMort' => $sexeMort, 'DateNaissance' => $dateNaissance, 'DateMort' => $dateMort, 'DateObseques' => $dateObseques, 'IdConcession' => NULL, 'IdCommunale' => NULL, 'IdReposoir' => $idReposoir]);
                    $id = $mortManager->add($addMort);
                    $addLogs = new Logs(['IdUtilisateur' => $_SESSION['IdUser'], 'MessageLogs' => "a ajouter un nouveau mort (n°$id)", 'StatusLogs' => "success"]);
                    $logsManager->add($addLogs);
                }
                alertBox("success", "L'ajout à été fait.", "mort-details.php?id=$id", 1500);
            } catch (PDOException $e) {
                if ($e->getCode() == 20000) {
                    alertBox("danger", "La concession est pleine.", "add-mort.php", 1500);
                } else {
                    alertBox("danger", "La demande n'a pas pu aboutir.", NULL, NULL);
                }
            }
        } else {
            alertBox("danger", "L'ajout n'à pas pu être fait car : $errorText ", NULL, NULL);
        }
    }

    if (isset($_SESSION['username'])) {
    ?>

        <div class="container">
            <br>
            <h1 class="main-title text-center">Ajout d'un mort</h1>
            <br>
            <form method="post" action="" id="formAjoutMort" novalidate>
                <div class="text-center">
                    <div class="form-row">
                        <div class="form-control-group col-md-1">
                        </div>

                        <!-- Nom de famille -->
                        <div class="form-control-group col-md-3">
                            <input type="text" pattern="[-a-zA-Z0-9ïîôöêëâäÄÂÊËÎÏÖÔ\s]{1,50}" class="form-control" name="nomMort" id="nomMort" placeholder="Nom de famille">
                            <div class="invalid-feedback">
                                Le nom de famille est de maximum 50 caractères.
                            </div><br>
                        </div>

                        <!-- Nom de jeune fille -->
                        <div id="nomJeuneFille" class="form-control-group col-md-3">
                            <input type="text" pattern="[-a-zA-Z0-9ïîôöêëâäÄÂÊËÎÏÖÔ\s]{1,50}" class="form-control" name="nomJeuneFilleMort" id="nomJeuneFilleMort" placeholder="Nom de jeune fille">
                            <div class="invalid-feedback">
                                Le prénom est de maximum 50 caractères et de minimum 2 caractères.
                            </div><br>
                        </div>

                        <!-- Prénom -->
                        <div class="form-control-group col-md-3">
                            <input type="text" pattern="[-a-zA-Z0-9ïîôöêëâäÄÂÊËÎÏÖÔ\s]{2,40}" class="form-control" name="prenomMort" id="prenomMort" placeholder="Prénom">
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
                                <input type="radio" class="custom-control-input" id="homme" name="sexeMort" value="homme" onchange="aff_sexe('homme')" checked>
                                <label class="custom-control-label" for="homme">Homme</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="femme" name="sexeMort" value="femme" onchange="aff_sexe('femme')">
                                <label class="custom-control-label" for="femme">Femme</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="n/a" name="sexeMort" value="n/a" onchange="aff_sexe('n/a')">
                                <label class="custom-control-label" for="n/a">Non Renseigner</label>
                            </div>
                            <div class="invalid-feedback">
                                Le type est obligatoire
                            </div>
                        </div>

                        <div class="text-center form-control-group col-md-7">
                            <label class="md-3" for="sexeMort">Lieu d'enterrement :</label><br>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="civile" name="typeConcession" value="civile" onchange="aff_type('civile')" checked>
                                <label class="custom-control-label" for="civile">Tombe civile</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="columbarium" name="typeConcession" value="columbarium" onchange="aff_type('columbarium')">
                                <label class="custom-control-label" for="columbarium">Columbarium</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="communale" name="typeConcession" value="communale" onchange="aff_type('communale')">
                                <label class="custom-control-label" for="communale">Tombe Communale</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="reposoir" name="typeConcession" value="reposoir" onchange="aff_type('reposoir')">
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
                            <div class="form-group">
                                <select class="form-control" id="idCivile" name="idCivile">
                                    <option selected hidden>-- Choisir le numero de concession --</option>
                                    <?php
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
                            <div class="form-group">
                                <select class="form-control" id="idColumbarium" name="idColumbarium">
                                    <option selected hidden>-- Choisir le numero de casier du columbarium --</option>
                                    <?php
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
                            <div class="form-group">
                                <select class="form-control" id="idCommunale" name="idCommunale">
                                    <option selected hidden>-- Choisir le numero de tombe communale --</option>
                                    <?php
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
                            <div class="form-group">
                                <select class="form-control" id="idReposoir" name="idReposoir">
                                    <option selected hidden>-- Choisir le numero de casier du reposoir --</option>
                                    <?php
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
                        <input type="submit" value="Ajouter" class="btn btn-success btn-lg" name="ajoutMort">
                    </div>
                </div>
            </form>
        </div>
        <script>
            aff_sexe("homme");
            aff_type("civile");
        </script>
        <script>
            (function() {
                "use strict"
                window.addEventListener("load", function() {
                    var form = document.getElementById("formAjoutMort")
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
    <?php } else {
        echo "<script>location.href='index.php'</script>";
    }  ?>
</body>

</html>