<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="icon" href="./assets/img/logo.ico" />
    <title>Plan cimetière <?php echo $id ?> - Mairie Belvianes-et-Cavirac</title>
</head>

<body>
    <?php require('./include/header.inc.php');
    require('./include/fonctions.inc.php');
    verifEntete();

    if (isset($_SESSION['username'])) {
    ?>
        <div class="container">
            <br>
            <div class="text-center">
                <a style="text-decoration: none;" href="./assets/img/plan-cimetiere.png" target="_blank">
                    <img src="./assets/img/plan-cimetiere.png" width=75%>
                </a>
                <?php if (isset($_SESSION['username'])) { ?>
                    <a href="./update-plan.php"><img src="./assets/img/edit.png" style="border:none;" width="30px"></a>
                <?php } ?>
            </div>
            <br>
        </div>
    <?php
    } else {
        echo "<script>location.href='index.php'</script>";
    }
    ?>
</body>

</html>