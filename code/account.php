<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <link rel="icon" href="./assets/img/logo.ico" />
  <title>Mon compte - Mairie Belvianes-et-Cavirac</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
</head>

<body>
  <?php
  require('./include/header.inc.php');
  require('./include/bdd.inc.php');
  require('./include/fonctions.inc.php');
  verifEntete();

  if ($_SESSION['poste'] == "administrateur" || $_SESSION['poste'] == "maire") {
    generationEnteteAccount();
  }

  if (isset($_SESSION['username'])) { ?>
    <div class="container">
      <br>
      <canvas id="myChart" style="width:100%;max-width:1000px"></canvas>

    </div>

  <?php
  } else {
    echo "<script>location.href='index.php'</script>";
  }
  ?>

  <script type="text/javascript">
    var xValues = ["Tombe (Civile)", "Casier (Columbarium)", "Tombe Communale", "Casier (Reposoir)", "Mort"];
    var yValues = [<?php AfficheNombreTombeCivile() ?>, <?php AfficheNombreColumbarium() ?>, <?php AfficheNombreTombeCommunale() ?>, <?php AfficheNombreReposoir() ?>, <?php AfficheNombreMort() ?>, 0];

    var myChart = new Chart("myChart", {
      type: 'bar',
      data: {
        labels: xValues,
        datasets: [{
          data: yValues,
          backgroundColor: [
            'rgba(255, 159, 64, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(52, 152, 219, 0.2)',
            'rgba(204, 142, 53, 0.2)',
            'rgba(231, 76, 60, 0.2)'
          ],
          borderColor: [
            'rgba(255, 159, 64, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(52, 152, 219, 1)',
            'rgba(204, 142, 53, 1)',
            'rgba(231, 76, 60, 1)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        legend: {
          display: false,
        },
      }
    });
  </script>
</body>

</html>