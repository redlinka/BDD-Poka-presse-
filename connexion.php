/**
 * Page de connexion pour l'application Poka Presse.
 *
 * Fonctionnalités principales :
 * - Affiche un formulaire de connexion demandant le login (nom d'utilisateur ou email) et le mot de passe.
 * - À la soumission du formulaire (méthode POST), vérifie que les champs sont remplis.
 * - Récupère les informations de connexion de l'utilisateur et hashe le mot de passe avec md5.
 * - Inclut le fichier de connexion à la base de données (`includes/cnx.php`).
 * - Prépare et exécute une requête SQL pour vérifier si un utilisateur existe avec le login et le mot de passe fournis.
 * - Si l'utilisateur est trouvé, initialise la session avec les informations de l'utilisateur et redirige vers `tableau_de_bord.php`.
 * - Si les identifiants sont incorrects, affiche un message d'erreur.
 * - Si le formulaire n'est pas soumis, déconnecte l'utilisateur en supprimant les variables de session.
 *
 * Sécurité :
 * - Utilisation de requêtes préparées pour éviter les injections SQL.
 * - Le mot de passe est stocké et comparé sous forme de hash md5 (à améliorer pour une meilleure sécurité).
 *
 * Dépendances :
 * - Fichier de connexion à la base de données : `includes/cnx.php`
 * - Feuille de style : `css/stylesheet.css`
 *
 * @package PokaPresse
 */

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
        <form method="POST" action="connexion.php">
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

                // On vérifie si l'utilisateur est connecté             
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

                    header('location: tableau_de_bord.php');
                }
                // si les credantials ne correspondent pas
                else {
                    echo "<p class='error'>Login ou mot de passe incorrect</p>";
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