<?php

include 'includes/cnx.php';

// Fonction récursive pour afficher la hiérarchie
function afficherRubriques($num_rubrique_ancetre = null, $niveau = 0) {
    $stmt = $cnx->query("SELECT nom_rubrique FROM Rubrique WHERE num_rubrique_ancetre = $num_rubrique_ancetre");
    if ($num_rubrique_ancetre) $stmt->bindParam(':num_rubrique_ancetre', $num_rubrique_ancetre, PDO::PARAM_INT);
    $stmt->execute();
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