<?php
function verifEntete()
{
    if (!empty($_SESSION['username'])) {
        echo '<script>aff_cach_input("employe");</script>';
    } else {
        echo '<script>aff_cach_input("pasemploye");</script>';
    }
}

function generationEnteteAccount()
{
    echo ('<div class="nav-scroller bg-body shadow-sm">
    <nav class="navbar navbar-expand-lg" aria-label="Secondary navigation">
    <ul class="navbar-nav mr-auto">
    <li class="nav-item">
    <a href="./account.php"><input type="button" class="btn btn-secondary" value="Dashboard"></a>
    </li>
    &ensp;
    <li class="nav-item">
    <a href="./logs.php"><input type="button" class="btn btn-info" value="Logs"></a>
    </li>
    </ul>
    </nav>
    </div>');
}


function alertBox($couleur, $message, $link, $time)
{
    echo ("<div class='alert alert-$couleur' role='alert'>$message</div>");
    if (strlen($link) > 1 && strlen($time) > 1) {
        echo ("<script>setTimeout(function() { window.location.href = './$link';}, $time);</script>");
    }
}

function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}


// Fonctions SQL

function AfficheNombreTombeCivile()
{
    $pdo = new PDO('mysql:host=localhost;dbname=cimetiere_bdd', 'root', '');
    $stmt = $pdo->query('SELECT NombreTombeCivile();');
    while ($row = $stmt->fetch()) {
        echo ($row[0]);
    }
}

function AfficheNombreColumbarium()
{
    $pdo = new PDO('mysql:host=localhost;dbname=cimetiere_bdd', 'root', '');
    $stmt = $pdo->query('SELECT NombreColumbarium();');
    while ($row = $stmt->fetch()) {
        echo ($row[0]);
    }
}


function AfficheNombreTombeCommunale()
{
    $pdo = new PDO('mysql:host=localhost;dbname=cimetiere_bdd', 'root', '');
    $stmt = $pdo->query('SELECT NombreTombeCommunale();');
    while ($row = $stmt->fetch()) {
        echo ($row[0]);
    }
}


function AfficheNombreReposoir()
{
    $pdo = new PDO('mysql:host=localhost;dbname=cimetiere_bdd', 'root', '');
    $stmt = $pdo->query('SELECT NombreReposoir();');
    while ($row = $stmt->fetch()) {
        echo ($row[0]);
    }
}

function AfficheNombreMort()
{
    $pdo = new PDO('mysql:host=localhost;dbname=cimetiere_bdd', 'root', '');
    $stmt = $pdo->query('SELECT NombreMort();');
    while ($row = $stmt->fetch()) {
        echo ($row[0]);
    }
}
