<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="icon" href="./assets/img/logo.ico" />
    <title>Logs - Mairie Belvianes-et-Cavirac</title>
</head>
<style>
    body {
        background: black;
        color: white;
    }

    pre code {
        font: 11pt/1.25 Monaco, monospace;
    }

    .debug {
        color: #f8f9fa;
    }

    .success {
        color: #28a745;
    }

    .warn {
        color: #ffc107;
    }

    .danger {
        color: #dc3545;
    }
</style>

<body>
    <?php require('./include/header.inc.php');
    require('./include/fonctions.inc.php');

    include('./classes/logs.class.php');
    include('./classes/logsManager.class.php');
    verifEntete();

    if ($_SESSION['poste'] == "administrateur" || $_SESSION['poste'] == "maire") {
        generationEnteteAccount();
    ?>
        <div class="container">
            <br>
            <div style="overflow-y:scroll; height: 35rem; background: black; color: white; padding-left: 2rem;">
                <code>
                    <?php echo ($logsManager->getList()); ?>
                </code>
            </div>
        </div>
    <?php
    } else {
        echo "<script>location.href='account.php'</script>";
    }  ?>
</body>

</html>