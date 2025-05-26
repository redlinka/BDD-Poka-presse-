<link rel="stylesheet" href="/BDD-Poka-presse/css/Phpsheet.css">
<?php

include 'includes/cnx.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vers = isset($_POST['version']) ? (int)$_POST['version'] : null;
    $code = isset($_POST['code']) ? $_POST['code'] : null;
} else {
    $vers = isset($_GET['version']) ? (int)$_GET['version'] : null;
    $code = isset($_GET['code']) ? $_GET['code'] : null;
}

if ($vers === null) {
    http_response_code(400);
    echo 'numéro d\'article manquant';
    exit;
}

$stmt = $cnx->prepare("
    SELECT m.*, a.nom AS maquettiste_nom, a.prenom AS maquettiste_prenom
    FROM maquette m
    LEFT JOIN acteur a ON m.mat_maquettiste = a.matricule
    WHERE m.num_vers = :version
");
$stmt->execute(['version' => $vers]);
$maquette = $stmt->fetch(PDO::FETCH_ASSOC);
if ($maquette) {
    echo "<h2>Maquette Version " . htmlspecialchars($maquette['num_vers']) . "</h2>";
    echo "<p><strong>Maquettiste :</strong> " . htmlspecialchars($maquette['maquettiste_nom']) . " " . htmlspecialchars($maquette['maquettiste_prenom']) . "</p>";
    echo "<p><strong>Date de création :</strong> " . htmlspecialchars($maquette['date_creation']) . "</p>";
    echo "<p><strong>Fichier :</strong> <a href='" . htmlspecialchars($maquette['lien_maquette']) . "'>" . htmlspecialchars($maquette['lien_maquette']) . "</a></p>";
    echo "<form method='post' action='detail_maquette.php'>
            <input type='hidden' name='code' value='" . htmlspecialchars($code) . "'>
            <input type='hidden' name='version' value='" . htmlspecialchars($vers) . "'>
            <input type='submit'>choisir cette maquette</input>
          </form>";
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $stmt = $cnx->prepare("UPDATE numero SET num_vers = :version WHERE code = :code");
        $stmt->execute([
            'version' => $vers,
            'code' => $code
        ]);
        $stmt = $cnx->prepare("UPDATE numero SET date_publication = NOW() WHERE code = :code");
        $stmt->execute([
            'code' => $code
        ]);
        $stmt = $cnx->prepare("UPDATE article SET publie = TRUE WHERE num_rubrique IN (SELECT num_rubrique FROM contient WHERE code = :code)");
        $stmt->execute(['code' => $code]);
        echo "<p>Maquette sélectionnée avec succès.</p>";
    }
} else {
    echo "<p>Aucune maquette trouvée</p>";
}
