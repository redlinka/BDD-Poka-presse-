
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/stylesheet.css">

        <title>Poka Presse</title>
    </head>
    <body>
        <h1>Bienvenue sur Poka Presse</h1>
        <form method="POST" action="index.php">
            <h2>Connexion</h2>
            <label for="login">Nom d'utilisateur ou adresse mail</label><br>
            <input type="text" name="login" placeholder="Entrez votre login..."  id="login" required><br><br>
            <label for="login">Mot de passe</label><br>
            <input type="password" name="password" placeholder="Entrez votre mot de passe..." id="pass" required><br><br>
            <input type="submit" value="Se connecter" id="submitBtn">
        </form>
        <?php
            session_start();

            // si l'utilisateur vient de remplir tout le form 
            if (!empty($_POST['login']) && !empty($_POST['password'])){

                // recup des info user
                $login = $_POST['login'];
                $pass = $_POST['password'];
                $hashedPass = md5($_POST['password']);

                
                // on inclut le fichier de connexion
                include __DIR__ . '/includes/cnx.php';

                $stmt = $cnx->query("SET search_path = 'poka_presse'");

                
                $stmt = $cnx->prepare("SELECT * FROM acteur WHERE mail = :login AND pass = :pass");
                $stmt->execute([':login' => $login, ':pass' => $hashedPass]);
                
                $user = $stmt->fetch(PDO::FETCH_OBJ);
                
                if ($user){
                    // si les credantials correspondent
                    // on connecte l'utilisateur en mettant ces 
                    // credantials dans la session 
                    $_SESSION['login'] = $login;
                    $_SESSION['pass'] = $user->pass;
                    $_SESSION['fonction'] = $user->fonction;
                    $_SESSION['id'] = $user->matricule;

                    // on redirect l'utilisateur sur la page home
                    if ($_SESSION['fonction'] == 'AD'){
                        header('location: /users/admin/index.php');
                    }
                    else if ($_SESSION['fonction'] == 'CR'){
                        header('location: /users/comite_redaction/index.php');
                    }
                    else if ($_SESSION['fonction'] == 'MQ'){
                        header('location: /users/maquettiste/index.php');
                    }
                    exit;
                }
                // recupération des données de tout la première ligne 
                else { 
                    // gestion des erreurs 
                    echo "<p class=\"err\">mauvais mot de passe ou login</p>";
                    unset($err);
                }
            }
            // si l'utilisateur n'a pas envoyé le form
            else {

                // on déconnecte l'utilisateur 
                // si l'utilisateur est connecté 
                if (isset($_SESSION['login']) && isset($_SESSION['pass']) && isset($_SESSION['id']) && isset($_SESSION['fonction'])){
                    unset($_SESSION['login']);
                    unset($_SESSION['fonction']);
                    unset($_SESSION['pass']);
                    unset($_SESSION['id']);
                }
            }    
        ?>
        
    </body>
</html>