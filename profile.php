/**
 * Affiche le profil d'un acteur à partir de son matricule passé en paramètre GET.
 *
 * - Récupère le matricule depuis l'URL (?matricule=).
 * - Si le matricule est absent, retourne une erreur 400.
 * - Recherche l'acteur correspondant dans la base de données.
 * - Affiche les informations de l'acteur (nom, prénom, fonction, statut, mail).
 * - Affiche un message si aucun acteur n'est trouvé.
 *
 * Dépendances :
 * - Requiert le fichier 'includes/cnx.php' pour la connexion à la base de données.
 * - Utilise PDO pour les requêtes SQL.
 *
 * Sécurité :
 * - Utilise htmlspecialchars pour éviter les failles XSS lors de l'affichage.
 *
 * Paramètres GET :
 * - matricule (int) : Identifiant unique de l'acteur à afficher.
 */
<?php

include 'includes/cnx.php';

$mat = isset($_GET['matricule']) ? (int)$_GET['matricule'] : null;

if ($mat === null) {
    http_response_code(400);
    echo 'matricule manquant';
    exit;
}

$stmt = $cnx->prepare("SELECT * FROM acteur WHERE matricule = :matricule");
$stmt->execute(['matricule' => $mat]);
$acteur = $stmt->fetch(PDO::FETCH_ASSOC);
if ($acteur) {
    echo "<h2>" . htmlspecialchars($acteur['nom']) ." ". htmlspecialchars($acteur['prenom']) .  "</h2>";
    if ($acteur['fonction'] == 'AD') {
        echo "<p>Fonction : Administrateur</p>";
    } elseif ($acteur['fonction'] == 'CR') {
        echo "<p>Fonction : Comite de redaction</p>";
    } elseif ($acteur['fonction'] == 'MQ') {
        echo "<p>Fonction : Maquettiste</p>";
    } elseif ($acteur['fonction'] == 'PG') {
        echo "<p>Fonction : Pigiste</p>";
    }
    echo "<p>matricule : " . htmlspecialchars($mat) . "</p>";
    if ($acteur['salarie'] != 0) {
        echo "<p>Statut : Salarie </p>";
    } else {
        echo "<p>Statut : travailleur independant</p>";
    }
    echo "<p>Mail : " . htmlspecialchars($acteur['mail']) . "</p>";
} else {
    echo "<p>Aucun numero en cours</p>";
}
?>
