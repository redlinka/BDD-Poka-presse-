
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
$sql2 = "
SELECT p.nom_pays, SUM(d.nb_ventes) AS total_ventes
FROM pays p
JOIN distribution d ON p.code_pays = d.code_pays
GROUP BY p.code_pays, p.nom_pays
ORDER BY total_ventes DESC
LIMIT 3";

$stmt = $cnx->query($sql);
$topPig = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h2>Top 3 Pigistes par Nombre d'Articles</h2>";
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>Nom</th><th>Prénom</th><th>Nombre d'articles</th></tr>";

if (count($topPig) > 0) {
    foreach ($topPig as $row) {
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

$stmt = $cnx->query($sql2);
$topPays = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h2>Top 3 des pays avec le plus de ventes</h2>";
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>Nom du pays</th><th>Nombre de ventes</th></tr>";

if (count($topPays) > 0) {
    foreach ($topPays as $row) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['nom_pays']) . "</td>";
        echo "<td>" . htmlspecialchars($row['total_ventes']) . " numéros vendus</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='3'>Aucun résultat</td></tr>";
}
echo "</table>";

// Footer
include 'includes/footer.php';
?>