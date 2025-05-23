/**
 * Tableau de bord principal pour les utilisateurs connectés.
 *
 * - Vérifie si l'utilisateur est connecté, sinon redirige vers la page de connexion.
 * - Récupère et affiche le prénom de l'utilisateur connecté.
 * - Affiche deux sections principales :
 *   - Liste des numéros en cours (via un iframe vers liste_en_cours.php)
 *   - Liste des numéros publiés (via un iframe vers liste_publie.php)
 * - Ajoute un script JavaScript pour intercepter les clics sur les liens à l'intérieur des iframes
 *   et rediriger la page principale vers l'URL du lien cliqué.
 *
 * Dépendances :
 * - Connexion à la base de données via 'includes/cnx.php'
 * - En-tête et pied de page via 'includes/header.php' et 'includes/footer.php'
 * - Variables de session : 'login' et 'id'
 */
<?php

include 'includes/cnx.php';

include 'includes/header.php';

// check si l'utilisateur est connecté
if (!isset($_SESSION['login'])) {
    header('Location: connexion.php');
    exit();
}
// recuperer le nom de l'utilisateur
$stmt = $cnx->prepare("SELECT prenom FROM acteur WHERE matricule = :id");
$stmt->execute([':id' => $_SESSION['id']]);
$user = $stmt->fetch(PDO::FETCH_OBJ);

// Afficher l'utilisateur
echo "<h1>Bienvenue, " . htmlspecialchars($user->prenom) . "</h1>";
?>

<h2>Liste des numéros en cours</h2>
<iframe src="liste_en_cours.php" class="panel" id="frame_en_cours"></iframe>

<h2>Liste des numéros publiés</h2>
<iframe src="liste_publie.php" class="panel" id="frame_publie"></iframe>

<script>
    function handleIframeLinks(iframeId) {
        document.getElementById(iframeId).addEventListener('load', function() {
            try {
                const iframeDoc = this.contentDocument || this.contentWindow.document;
                const links = iframeDoc.querySelectorAll('a');
                links.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = this.getAttribute('href');
                        top.location.href = url;
                    });
                });
            } catch (error) {}
        });
    }

    handleIframeLinks('frame_en_cours');
    handleIframeLinks('frame_publie');
</script>

<?php include 'includes/footer.php'; ?>
