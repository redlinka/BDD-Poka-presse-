<link rel="stylesheet" href="/BDD-Poka-presse/css/Phpsheet.css">
<?php
include 'includes/cnx.php';

$stmt = $cnx->query("SELECT * FROM numero WHERE date_publication IS NULL AND num_vers IS NULL");
$numeros = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($numeros) > 0) {
    foreach ($numeros as $row) {
        // On passe le code de la ligne dans l'URL
        $link = "maquettes.php?code=" . urlencode($row['code']);
        echo "<a href='" . htmlspecialchars($link) . "'>" . htmlspecialchars($row['code']) . "</a></br>";
    }
} else {
    echo "<p>Aucun numero en cours</p>";
}
?>