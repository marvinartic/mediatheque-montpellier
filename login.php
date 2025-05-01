<?php
session_start();
require_once 'includes/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $mdp = $_POST['mot_de_passe'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($mdp, $user['mot_de_passe'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nom'] = $user['nom'];

       
        header('Location: profil.php');
    exit;
    } else {
        $message = "❌ Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
  <div class="login-container">
    <form method="post">
      <h1>Se connecter</h1>
      <h2 class="subtitle">Connectez-vous pour accéder à votre espace personnel</h2>
      <input type="email" name="email" placeholder="E-mail*" required>
      <input type="password" name="mot_de_passe" placeholder="Mot de passe*" required>

      <div class="remember-me">
        <input type="checkbox" id="remember" name="remember">
        <label for="remember">Se souvenir de moi</label>
      </div>

      <div class="forgot-password">
        <a href="#">Mot de passe oublié ?</a>
      </div>

      <button type="submit">Connexion</button>

      <div class="form-footer">
        <a href="index.php">← Retour à l’accueil</a> |
        <a href="register.php">Créer un compte</a>
      </div>
    </form>
  </div>
</body>

</html>
