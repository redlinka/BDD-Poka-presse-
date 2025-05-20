/**
 * Ce script récupère tous les enregistrements de la table 'numero' où 'date_publication' et 'num_vers' sont NULL,
 * indiquant les numéros actuellement en cours. Pour chaque enregistrement correspondant, il génère un lien vers 'numero.php'
 * avec le 'code' concerné en paramètre d'URL. Si aucun enregistrement n'est trouvé, un message indique qu'aucun numéro n'est en cours.
 *
 * Dépendances :
 * - Nécessite une connexion à la base de données via 'includes/cnx.php'.
 *
 * Sortie :
 * - Une liste de liens vers 'numero.php' pour chaque numéro en cours, ou un message si aucun n'est trouvé.
 */

<?php
include 'includes/cnx.php';

$stmt = $cnx->query("SELECT * FROM numero WHERE date_publication IS NULL AND num_vers IS NULL");
$numeros = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($numeros) > 0) {
    foreach ($numeros as $row) {
        // On passe le code de la ligne dans l'URL
        $link = "numero.php?code=" . urlencode($row['code']);
        echo "<a href='" . htmlspecialchars($link) . "'>" . htmlspecialchars($row['code']) . "</a></br>";
    }
} else {
    echo "<p>Aucun numero en cours</p>";
}
?>