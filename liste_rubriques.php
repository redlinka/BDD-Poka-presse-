<link rel="stylesheet" href="/BDD-Poka-presse/css/Phpsheet.css">
<?php

include 'includes/cnx.php';

// Fonction récursive pour afficher la hiérarchie
function afficherRubriques($num_rubrique_ancetre = null, $niveau = 0) {
    global $cnx;
    if ($num_rubrique_ancetre === null) {
        $stmt = $cnx->prepare("SELECT num_rubrique, nom_rubrique FROM Rubrique WHERE num_rubrique_ancetre IS NULL");
        $stmt->execute();
    } else {
        $stmt = $cnx->prepare("SELECT num_rubrique, nom_rubrique FROM Rubrique WHERE num_rubrique_ancetre = :num_rubrique_ancetre");
        $stmt->bindParam(':num_rubrique_ancetre', $num_rubrique_ancetre, PDO::PARAM_INT);
        $stmt->execute();
    }
    $rubriques = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($rubriques) {
        echo "<ul>";
        foreach ($rubriques as $rubrique) {
            echo "<li>" . htmlspecialchars($rubrique['nom_rubrique']);
            // Appel récursif pour les sous-rubriques
            afficherRubriques($rubrique['num_rubrique'], $niveau + 1);
            echo "</li>";
        }
        echo "</ul>";
    }
}

// Affichage de la hiérarchie
echo "<div class='hierarchie-rubriques'>";
afficherRubriques(null, 0);
echo "</div>";
?>