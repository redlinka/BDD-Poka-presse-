<link rel="stylesheet" href="/BDD-Poka-presse/css/Phpsheet.css">
<?php

include 'includes/cnx.php';

$code = $_GET['code'];
$stmt = $cnx->query("SELECT * FROM rubrique WHERE num_rubrique IN (SELECT num_rubrique FROM contient WHERE code = '$code')");
$hiera = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt = $cnx->query("SELECT * FROM numero WHERE code = '$code' AND date_publication IS NOT NULL");
$publie = $stmt->fetch(PDO::FETCH_ASSOC);
echo "<h2>Rubriques du numéro</h2>";
if ($hiera){
    echo "<ul>";
    foreach ($hiera as $row) {
        echo "<li>" . htmlspecialchars($row['nom_rubrique']) . "</li>";
        if ($publie) {
            $stmt = $cnx->prepare("SELECT * FROM article WHERE num_rubrique = :num_rubrique and publie IS TRUE");
            $stmt->execute(['num_rubrique' => $row['num_rubrique']]);
            $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $stmt = $cnx->prepare("SELECT * FROM article WHERE num_rubrique = :num_rubrique");$stmt = $cnx->prepare("SELECT * FROM article WHERE num_rubrique = :num_rubrique AND publie IS FALSE");
            $stmt->execute(['num_rubrique' => $row['num_rubrique']]);
            $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        if ($articles) {
            echo "<ul>";
            foreach ($articles as $article) {
                echo "<li><a href='detail_article_hierarchie.php?num=" . htmlspecialchars($article['num_article']) . "'>" . htmlspecialchars($article['titre']) . "</a></li>";
            }
            echo "</ul>";
        } else {
            echo "<ul><li>Aucun article trouvé dans cette rubrique.</li></ul>";
        }
    }
    echo "</ul>";
} else {
    echo "Aucune rubrique trouvée pour le code $code.";
}


?>