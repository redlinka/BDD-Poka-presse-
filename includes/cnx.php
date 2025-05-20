/**
 * Établit une connexion à une base de données PostgreSQL en utilisant les identifiants du fichier de configuration .env.
 *
 * Étapes :
 * 1. Charge les variables d'environnement (USER, PASS, DB, HOST) depuis le fichier config/.env.
 * 2. Tente de créer une nouvelle instance PDO pour PostgreSQL avec les identifiants chargés.
 * 3. Si la connexion échoue, intercepte la PDOException et affiche un message d'erreur ainsi que la configuration PHP.
 *
 * Variables :
 * @var array  $env   Variables d'environnement extraites du fichier .env.
 * @var string $user  Nom d'utilisateur de la base de données.
 * @var string $pass  Mot de passe de la base de données.
 * @var string $db    Nom de la base de données.
 * @var string $host  Hôte de la base de données.
 * @var PDO    $cnx   Instance PDO pour la connexion à la base de données.
 */

<?php
    $env = parse_ini_file(__DIR__ . '/../config/.env');
    $user =  $env["USER"];
    $pass = $env["PASS"];
    $db = $env["DB"];
    $host = $env["HOST"];

    try {
        $cnx = new PDO(
            "pgsql:host=$host;dbname=$db;", 
            $user, 
            $pass
        );

    }
    catch (PDOException $e) {
        echo "ERREUR : La connexion a échouée : ";
        echo $e;
        echo phpinfo();
    }

?>