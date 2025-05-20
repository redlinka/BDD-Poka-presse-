/**
 * This script retrieves and displays all published "numero" records from the database.
 * 
 * - Connects to the database using the included 'includes/cnx.php' file.
 * - Executes a query to select all rows from the 'numero' table where both 'date_publication' and 'num_vers' are not null.
 * - For each result, generates a hyperlink to 'numero.php' with the 'code' as a URL parameter.
 * - If no records are found, displays a message indicating that there are no finished numbers.
 *
 * @package PokaPresse
 * @file liste_publie.php
 */
<?php
include 'includes/cnx.php';

$stmt = $cnx->query("SELECT * FROM numero WHERE date_publication IS NOT NULL AND num_vers IS NOT NULL");
$numeros = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($numeros) > 0) {
    foreach ($numeros as $row) {
        // On passe le code de la ligne dans l'URL
        $link = "numero.php?code=" . urlencode($row['code']);
        echo "<a href='" . htmlspecialchars($link) . "'>" . htmlspecialchars($row['code']) . "</a></br>";
        echo "</tr>";
    }
} else {
    echo "<p>Aucun numero fini</p>";
}
?>