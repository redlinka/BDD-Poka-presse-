<link rel="stylesheet" href="/BDD-Poka-presse/css/Phpsheet.css">
<?php
include 'includes/cnx.php';

try {
    $cnx->beginTransaction();
    $cnx->exec("SET TRANSACTION ISOLATION LEVEL SERIALIZABLE");
    $stmt = $cnx->query("
        DELETE FROM article
        WHERE date_acceptation < NOW() - INTERVAL '6 months'
        AND publie = FALSE
    ");
    $cnx->commit();
} catch (PDOException $e) {
    $cnx->rollBack();
    die("Erreur lors de la suppression des articles : " . htmlspecialchars($e->getMessage()));
}

$stmt = $cnx->query("SELECT * FROM article WHERE publie IS FALSE ORDER BY date_acceptation DESC");
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($res) == 0) {
    echo "<p>Aucun article publié pour l'instant.</p>";
}
else {
    echo "<ul>";
    foreach ($res as $article) {
        $link = "detail_article.php?num=" . urlencode($article['num_article']);
        echo "<li class='article-link'><a href='" . htmlspecialchars($link) . "'>" . htmlspecialchars($article['titre']) . "</a></li>";
    }   
    echo "</ul>";
}
