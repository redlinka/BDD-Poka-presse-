<?php
include 'includes/cnx.php';

$stmt = $cnx->query("SELECT * FROM maquette");
$maq = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($maq) > 0) {
    foreach ($maq as $row) {
        // On passe le code de la ligne dans l'URL
        $link = "detail_maquette.php?version=" . urlencode($row['num_vers']) . "&code=" . urlencode($_GET['code']);
        echo "<a href='" . htmlspecialchars($link) . "'>" . htmlspecialchars($row['lien_maquette']) . "</a></br>";
    }
} else {
    echo "<p>Il n'y a aucune maquette</p>";
}
?>