/**
 * Cette page affiche les rubriques et leur(s) sous-rubrique(s) avec un système hiérarchique.
 * Fonctionnalités :
 * -----------------
 * - Inclut la connexion à la base de données et les templates d'en-tête/pied de page.
 * - Intègre 1 iframe listant chaque rubrique et ses sous-rubriques.
 * - Il est possible d'ajouter ou retirer une rubrique (les sous-rubriques ne sont pas supprimés, ils remontent d'un cran dans la hiérarchie).
 *
 * Sécurité :
 * ----------
 * - L'accès est restreint aux utilisateurs connectés avec des rôles spécifiques.
 * - Un accès non autorisé retourne un HTTP 403 et un message convivial.
 *
 * Dépendances :
 * -------------
 * - includes/cnx.php : Connexion à la base de données.
 * - includes/header.php : En-tête de la page.
 * - includes/footer.php : Pied de page.
 * - liste_acteurs.php : Liste des membres à afficher dans l'iframe.
*/

<?php

include 'includes/cnx.php';

include 'includes/header.php';
?>

<h1>Rubriques</h1>

<iframe class="frame" id="rubriques" src="hierarchie_rubriques.php"></iframe>

<?php include 'includes/footer.php'; ?>