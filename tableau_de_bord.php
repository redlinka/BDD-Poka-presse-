<?php

include 'includes/cnx.php';

include 'includes/header.php';

if (!isset($_SESSION['login'])) {
    header('Location: connexion.php');
    exit();
}
$stmt = $cnx->prepare("SELECT prenom FROM acteur WHERE matricule = :id");
$stmt->execute([':id' => $_SESSION['id']]);
$user = $stmt->fetch(PDO::FETCH_OBJ);

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
