/**
 * Fichier d'en-tête pour l'application Poka Presse.
 *
 * - Démarre une session si aucune n'est active.
 * - Génère l'en-tête HTML, incluant les balises meta et la feuille de style.
 * - Affiche un menu de navigation avec des liens selon le rôle de l'utilisateur :
 *   - Si la variable de session 'fonction' est 'AD' (Admin) ou 'CR' (Contributeur),
 *     des liens supplémentaires vers "Acteurs", "Articles" et "Stats" sont affichés.
 *   - Tous les utilisateurs voient les liens vers "Tableau de bord", "Rubriques", "Déconnexion" et leur profil.
 *   - Le lien du profil inclut dynamiquement l'identifiant de session 'id'.
 *
 * Hypothèses :
 * - Les variables de session 'fonction' et 'id' sont définies lors de l'authentification.
 * - Le fichier CSS 'css/stylesheet.css' existe et est accessible.
 *
 * @package PokaPresse
 */
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
        <link rel="stylesheet" href="css/stylesheet.css">

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
                    <li><a href="connexion.php">Deconnexion</a></li>
                    <li><a href="profile.php?matricule=<?php echo $_SESSION['id']; ?>">Profile</a></li>
            </ul>
        </nav>
    </header>