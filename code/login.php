<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="icon" href="./assets/img/logo.ico" />
    <title>Se connecter - Mairie Belvianes-et-Cavirac</title>
    <style>
        body {
            background-image: url("./assets/img/background.png");
            background-size: cover;
        }
    </style>
</head>

<body>
    <?php
    // include et require
    require('./include/bdd.inc.php');
    include('./classes/users.class.php');
    include('./classes/usersManager.class.php');

    include('./classes/logs.class.php');
    include('./classes/logsManager.class.php');

    include('./include/fonctions.inc.php');


    if (isset($_POST['connexion'])) {
        if ($user = $userManager->getUser($_POST['username'])) {

            if ($user->getMdpHash() == hash("sha256", $_POST['password'])) {
                session_start();
                $_SESSION['IdUser'] = $user->getIdUser();
                $_SESSION['username'] = $user->getUsername();
                $_SESSION['poste'] = $user->getPoste();
                $addLogs = new Logs(['IdUtilisateur' => $_SESSION['IdUser'], 'MessageLogs' => "s'est connecté", 'StatusLogs' => "debug"]);
                $logsManager->add($addLogs);
                header('Location: account.php');
            } else {
                echo '<script>alert("Adresse email ou mot de passe incorrect")</script>';
            }
        } else {
            $_SESSION['login'] = false;
            echo '<script>alert("Adresse email ou mot de passe incorrect")</script>';
        }
    }

    include('./include/header.inc.php');

    verifEntete();
    ?>

    <section width="100%" class="vh-100">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card shadow-2-strong" style="background-color: rgba(255,255,255, 0.7); border-radius: 1rem;">
                        <div class="card-body p-5 text-center">

                            <h3 class="mb-5">Se connecter</h3>

                            <form method="post" action="" class="signin-form">
                                <div class="form-group mt-3">
                                    <input type="text" class="form-control" name="username" placeholder="Nom d'utilisateur" required>
                                </div>
                                <div class="form-group">
                                    <input id="password-field" type="password" name="password" class="form-control" placeholder="Mot de passe" required>
                                    <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="Connexion" class="form-control btn btn-success px-3" name="connexion" />
                                </div>
                                <!-- <div class="form-group d-md-flex">
                                    <div class="w-50 text-left">
                                    </div>
                                    <div class="w-50 text-md-right">
                                        <a href="#">Forgot Password</a>
                                    </div>
                                </div> -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous">
    </script>
</body>

</html>