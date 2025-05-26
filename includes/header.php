<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/BDD-Poka-presse/css/Stylesheet.css">


        <title>Poka Presse</title>
    </head>
    <body>
    <header>
        <nav>
            <ul>
                <?php if ($_SESSION['fonction'] == 'AD' || $_SESSION['fonction'] == 'CR') : ?>
                    <li><a href="acteurs.php">Acteurs</a></li>
                    <li><a href="articles.php">Articles</a></li>
                    <li><a href="stats.php">Stats</a></li>
                <?php endif;?>
                    <li><a href="tableau_de_bord.php">Tableau de bord</a></li>
                    <li><a href="rubriques.php">Rubriques</a></li>
                    <li><a href="profile_perso.php?matricule=<?php echo $_SESSION['id']; ?>">Profile</a></li>
                    <li><a href="connexion.php">Deconnexion</a></li>
            </ul>
        </nav>
    </header>