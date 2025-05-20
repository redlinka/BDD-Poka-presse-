/**
 * Page : acteurs.php
 *
 * Description :
 * --------------
 * Cette page affiche la liste des "Membres de Poka Presse".
 * Elle inclut un contrôle d'accès basé sur la session, autorisant uniquement les utilisateurs ayant le rôle 'AD' (Admin)
 * ou 'CR' (Correspondant) à accéder à la page. Les utilisateurs non autorisés reçoivent une réponse 403 et un message d'erreur.
 *
 * Fonctionnalités :
 * -----------------
 * - Inclut la connexion à la base de données et les templates d'en-tête/pied de page.
 * - Intègre deux iframes :
 *     1. L'iframe "acteurs" charge 'liste_acteurs.php', qui liste les membres.
 *     2. L'iframe "details" affiche les détails d'un membre sélectionné.
 * - Utilise JavaScript pour intercepter les clics sur les liens dans l'iframe "acteurs",
 *   empêchant la navigation par défaut et chargeant le contenu lié dans l'iframe "details".
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

// check si l'utilisateur est connecté
if (!isset($_SESSION['login']) || $_SESSION['fonction'] != 'AD' && $_SESSION['fonction'] != 'CR') {
    http_response_code(403);
    echo '<div style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; display: flex; justify-content: center; align-items: center;">Vous n\'avez pas le droit d\'être ici.</div>';
    exit;
}

?>
<h1>Membres de Poka Presse</h1>
<iframe class="frame" id="acteurs" src="liste_acteurs.php"></iframe>
<iframe id="details"></iframe>
<script>
    document.getElementById('acteurs').addEventListener('load', function() {
        try {
            const iframeDoc = this.contentDocument || this.contentWindow.document;
            const links = iframeDoc.querySelectorAll('a');
            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = this.getAttribute('href');
                    document.getElementById('details').src = url;
                });
                    
                link.setAttribute('target', 'details');
            });
        } catch (error) {}
    });
</script>

<?php include 'includes/footer.php'; ?>