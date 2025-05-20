<?php
// Include database connection
include 'cnx.php';

// Header
include 'header.php';

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

$result = $cnx->query($sql);

echo "<h2>Top 3 Pigistes par Nombre d'Articles</h2>";
echo "<table border='1'>";
echo "<tr><th>Nom</th><th>Prénom</th><th>Nombre d'articles</th></tr>";

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
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
include 'footer.php';
?>