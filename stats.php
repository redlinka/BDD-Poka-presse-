/**
 * Affiche diverses statistiques sur les pigistes et les articles.
 *
 * - Vérifie si l'utilisateur est connecté et possède les droits d'accès ('AD' ou 'CR').
 * - Affiche le top 3 des pigistes ayant écrit le plus d'articles.
 * - Affiche le nombre total d'articles publiés.
 * - Affiche le nombre total de pigistes actifs (ayant écrit au moins un article).
 * - Affiche le nombre d'articles publiés ce mois-ci.
 *
 * Dépendances :
 * - Connexion à la base de données via 'includes/cnx.php'.
 * - En-tête et pied de page via 'includes/header.php' et 'includes/footer.php'.
 *
 * Sécurité :
 * - Utilise htmlspecialchars pour échapper les valeurs affichées.
 */

<?php
// Include database connection
include 'includes/cnx.php';

// Header
include 'includes/header.php';

// check si l'utilisateur a les droits d'accès
if (!isset($_SESSION['login']) || $_SESSION['fonction'] != 'AD' && $_SESSION['fonction'] != 'CR') {
    http_response_code(403);
    echo '<div style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; display: flex; justify-content: center; align-items: center;">Vous n\'avez pas le droit d\'être ici.</div>';
    exit;
}

// Query to get top 3 actors by article count
$sql = "
SELECT a.nom, a.prenom, e.article_count
FROM acteur a
JOIN (
    SELECT mat_pigiste, COUNT(*) AS article_count
    FROM ecriture
    GROUP BY mat_pigiste
    ORDER BY article_count DESC
    LIMIT 3
) e ON a.matricule = e.mat_pigiste
ORDER BY e.article_count DESC
";

$stmt = $cnx->query($sql);
$top = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h2>Top 3 Pigistes par Nombre d'Articles</h2>";
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>Nom</th><th>Prénom</th><th>Nombre d'articles</th></tr>";

if (count($top) > 0) {
    foreach ($top as $row) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['nom']) . "</td>";
        echo "<td>" . htmlspecialchars($row['prenom']) . "</td>";
        echo "<td>" . htmlspecialchars($row['article_count']) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='3'>Aucun résultat</td></tr>";
}
echo "</table>";

// Footer
include 'includes/footer.php';
?>