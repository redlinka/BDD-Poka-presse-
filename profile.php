<link rel="stylesheet" href="/BDD-Poka-presse/css/Phpsheet.css">
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
        $stmt = $cnx->prepare("SELECT notoriete FROM pigiste WHERE mat_pigiste = :matricule");
        $stmt->execute(['matricule' => $mat]);
        $pigiste = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<p>Notoriete : " . htmlspecialchars($pigiste['notoriete']) . " €</p>";
    }
    echo "<p>matricule : " . htmlspecialchars($mat) . "</p>";
    if ($acteur['salarie'] != 0) {
        echo "<p>Salarie : Oui </p>";
    } else {
        echo "<p>Salarie : Non</p>";
    }
    echo "<p>Mail : " . htmlspecialchars($acteur['mail']) . "</p>";
} else {
    echo "<p>Aucun numero en cours</p>";
}
?>
