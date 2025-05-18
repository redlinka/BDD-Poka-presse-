<?php
include 'includes/cnx.php';

include 'includes/header.php';

// check si l'utilisateur est connecté
if (!isset($_SESSION['login'])) {
    header('Location: /index.php');
    exit();
}

// recuperer le nom de l'utilisateur
$stmt = $cnx->prepare("SELECT prenom FROM acteur WHERE matricule = :id");
$stmt->execute([':id' => $_SESSION['id']]);
$user = $stmt->fetch(PDO::FETCH_OBJ);

// Afficher l'utilisateur
echo "<h1>Bienvenue, " . htmlspecialchars($user->prenom) . "</h1>";
?>

<?php include 'includes/footer.php'; ?>
