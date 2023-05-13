<?php

if (isset($_POST['deconnexion'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
}

if (isset($_POST['moncompte'])) {
    header("Location: account.php");
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand mb-0 h1" href="./index.php"><img src="./assets/img/logo.png" width="30" height="30" class="d-inline-block align-top" alt=""> Mairie de Belvianes-et-Cavirac (11)</a>
    <ul class="navbar-nav mr-auto">
        <div id="employe">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="menuderoulant" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Concessions</a>
                <div class="dropdown-menu" aria-labelledby="menuderoulant">
                    <a class="dropdown-item" href="./concession.php">Recherher une concession</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="./add-concession.php">Ajouter une concession</a>
                    <a class="dropdown-item" href="./delete-concession.php">Supprimer une concession</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="menuderoulant" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Tombes communales</a>
                <div class="dropdown-menu" aria-labelledby="menuderoulant">
                    <a class="dropdown-item" href="./tombecommunale.php">Recherher une tombe</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="./add-tombecommunale.php">Ajouter une tombe</a>
                    <a class="dropdown-item" href="./delete-tombecommunale.php">Supprimer une tombe</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="menuderoulant" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Reposoir</a>
                <div class="dropdown-menu" aria-labelledby="menuderoulant">
                    <a class="dropdown-item" href="./reposoir.php">Recherher un reposoir</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="./add-reposoir.php">Ajouter un reposoir</a>
                    <a class="dropdown-item" href="./delete-reposoir.php">Supprimer un reposoir</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="menuderoulant" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Morts</a>
                <div class="dropdown-menu" aria-labelledby="menuderoulant">
                    <a class="dropdown-item" href="./mort.php">Recherher un mort</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="./add-mort.php">Ajouter un mort</a>
                    <a class="dropdown-item" href="./delete-mort.php">Supprimer un mort</a>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="./plan.php">Plan cimetière</a>
            </li>
        </div>
    </ul>
    <form method="POST" class="form-inline mt-md-0">
        <?php if (!empty($_SESSION['username'])) {
        ?>
            <li class="nav-item">
                <button class="btn btn-success" name="moncompte"><span class="material-symbols-outlined">
                        <span class="material-symbols-outlined">
                            person
                        </span>
                </button>
                <button class="btn btn-danger" name="deconnexion"><span class="material-symbols-outlined">
                        logout
                    </span></button>
            </li>
        <?php } else {
        ?>
            <li class="nav-item">
                <a href="./login.php"><button type="button" class="btn btn-success">S'identifier</button></a>
            </li>
        <?php } ?>
    </form>
</nav>