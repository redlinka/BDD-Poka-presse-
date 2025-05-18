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

<?php include 'includes/footer.php'; ?>
