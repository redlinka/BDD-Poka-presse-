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