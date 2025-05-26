<?php
include 'includes/cnx.php';

$stmt = $cnx->query("SELECT * FROM acteur WHERE fonction = 'AD'ORDER BY nom");
$ad = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt = $cnx->query("SELECT * FROM acteur WHERE fonction = 'CR'ORDER BY nom");
$cr = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt = $cnx->query("SELECT * FROM acteur WHERE fonction = 'MQ'ORDER BY nom");
$mq = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt = $cnx->query("SELECT * FROM acteur WHERE fonction = 'PG'ORDER BY nom");
$pg = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h3>Administrateurs</h3>";
if (count($ad) > 0) {
    echo "<ul>";
    foreach ($ad as $acteur) {
        $link = "profile.php?matricule=" . urlencode($acteur['matricule']);
        echo "<li class='acteur-link'><a href='" . htmlspecialchars($link) . "'>" . htmlspecialchars($acteur['nom']) . " " . htmlspecialchars($acteur['prenom']) . "</a></li>";
    }
    echo "</ul>";
} else {
    echo "<p>Aucun administrateur pour l'instant.</p>";
}

echo "<h3>Comite de redaction</h3>";
if (count($cr) > 0) {
    echo "<ul>";
    foreach ($cr as $acteur) {
        $link = "profile.php?matricule=" . urlencode($acteur['matricule']);
        echo "<li class='acteur-link'><a href='" . htmlspecialchars($link) . "'>" . htmlspecialchars($acteur['nom']) . " " . htmlspecialchars($acteur['prenom']) . "</a></li>";
    }
    echo "</ul>";
} else {
    echo "<p>Aucun membre du commite de redaction pour l'instant.</p>";
}

echo "<h3>Maquettistes</h3>";
if (count($mq) > 0) {
    echo "<ul>";
    foreach ($mq as $acteur) {
        $link = "profile.php?matricule=" . urlencode($acteur['matricule']);
        echo "<li class='acteur-link'><a href='" . htmlspecialchars($link) . "'>" . htmlspecialchars($acteur['nom']) . " " . htmlspecialchars($acteur['prenom']) . "</a></li>";
    }
    echo "</ul>";
} else {
    echo "<p>Aucun maquettiste pour l'instant.</p>";
}

echo "<h3>Pigistes</h3>";
if (count($pg) > 0) {
    echo "<ul>";
    foreach ($pg as $acteur) {
        $link = "profile.php?matricule=" . urlencode($acteur['matricule']);
        echo "<li class='acteur-link'><a href='" . htmlspecialchars($link) . "'>" . htmlspecialchars($acteur['nom']) . " " . htmlspecialchars($acteur['prenom']) . "</a></li>";
    }  
    echo "</ul>";
} else {
    echo "<p>Aucun pigiste pour l'instant.</p>";
}
?>