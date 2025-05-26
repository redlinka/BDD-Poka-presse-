<?php

include 'includes/cnx.php';

$num = isset($_GET['num']) ? (int)$_GET['num'] : null;

if ($num === null) {
    http_response_code(400);
    echo 'numéro dárticle manquant';
    exit;
}

$stmt = $cnx->prepare("
    SELECT 
        a.*,
        ac.nom,
        ac.prenom,
        r.nom_rubrique
    FROM 
        article a
    JOIN 
        rubrique r ON a.num_rubrique = r.num_rubrique
    JOIN 
        ecriture e ON a.num_article = e.num_article
    JOIN 
        acteur ac ON e.mat_pigiste = ac.matricule
    WHERE 
        a.num_article = :num
");
$stmt->execute(['num' => $num]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if ($article) {
    echo "<h2>" . htmlspecialchars($article['num_article']) ." ". htmlspecialchars($article['titre']) . "</h2>";
    echo "<p><strong>Auteur :</strong> " . htmlspecialchars($article['nom']) ." ". htmlspecialchars($article['prenom']) . "</p>";
    echo "<p><strong>Rubrique :</strong> " . htmlspecialchars($article['nom_rubrique']) . "</p>";
    echo "<p><strong>Chapeau :</strong> " . htmlspecialchars($article['chapeau']) . "</p>";
    echo "<p><strong>Contenu :</strong> " . htmlspecialchars($article['lien_contenu']) . "</p>";
    echo "<p><strong>Nombre de feuillets :</strong> " . htmlspecialchars($article['nb_feuillets']) . "</p>";
    echo "<p><strong>Date d'acceptation :</strong> " . htmlspecialchars($article['date_acceptation']) . "</p>";
    if ($article['publie']) {
        echo "<p><strong>Statut :</strong> Publié</p>";
    } else {
        echo "<p><strong>Statut :</strong> Non publié</p>";
    }
    $stmt = $cnx->prepare("
        SELECT 
            lien_image
        FROM 
            image i
        WHERE
            i.num_image IN (
                SELECT l.num_image FROM liaison l WHERE l.num_article = :num
            )
    ");
    $stmt->execute(['num' => $num]);
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($images) > 0) {
        echo "<p><strong>Images :</strong></p>";
        echo "<ul>";
        foreach ($images as $image) {
            echo "<li>" . htmlspecialchars($image['lien_image']) . "</li>";
        }
        echo "</ul>";
    }

} else {
    echo "<p>Aucun article trouvé avec le numéro " . htmlspecialchars($num) . ".</p>";
}