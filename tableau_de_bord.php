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

<h2>Tableau de bord</h2>
<div class="horizontal">
    <div class="vertical">
        <h2>Liste des numéros en cours</h2>
        <iframe src="liste_en_cours.php" class="panel" id="frame_en_cours"></iframe>
        <form action ="tableau_de_bord.php" method="post">
            <label for="code">Code du numéro :</label>
            <input type="text" name="code" id="code" required>
            <button type="submit" class="btn">créer le numéro</button>
        </form>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['code'])) {
            $code = htmlspecialchars($_POST['code']);
            $stmt = $cnx->prepare("INSERT INTO numero (code, date_publication, num_vers) VALUES (:code, NULL, NULL)");
            $stmt->execute([':code' => $code]);
            header('Location: numero.php?code=' . urlencode($code));
            exit();
        }
        ?>
    </div>
        
    <div class="vertical">
        <h2>Liste des numéros publiés</h2>
        <iframe src="liste_publie.php" class="panel" id="frame_publie"></iframe>
    </div>
</div>
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
