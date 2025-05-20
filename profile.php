<?php

include 'includes/cnx.php';

$id = $_GET['id'] ?? null;

if ($id === null) {
    http_response_code(400);
    echo 'ID manquant';
    exit;
}

$stmt = $cnx->query("SELECT * FROM acteur WHERE matricule = :id");