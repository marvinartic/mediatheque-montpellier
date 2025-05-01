<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>La Maison du Livre</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">
</head>
<body>

<header>
  <div class="container">
    <h1>LA MAISON DU LIVRE</h1>
    <nav>
      <a href="#services">Services</a>
      <a href="#">À propos</a>
      <a href="#">Contact</a>

      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="profil.php">👤 Mon profil</a>
        <a href="logout.php" class="signup">Se déconnecter</a>
      <?php else: ?>
        <a href="login.php" class="login">Se connecter</a>
        <a href="register.php" class="signup">S’inscrire</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

<section class="hero">
  <div class="content">
    <h2>Bienvenue à la Maison du Livre</h2>
    <p>Une médiathèque numérique pour tous vos emprunts</p>
    <?php if (!isset($_SESSION['user_id'])): ?>
      <a href="login.php" class="cta">Se connecter pour accéder à votre espace</a>
    <?php else: ?>
      <a href="profil.php" class="cta">Accéder à mon espace personnel</a>
    <?php endif; ?>
  </div>
</section>

<section id="services">
  <h2>Nos services</h2>
  <div class="services-grid">
    <div class="service-card">
      <div class="icon">📚</div>
      <h3>Emprunt de livres</h3>
      <p>Découvrez notre catalogue riche et varié.</p>
    </div>
    <div class="service-card">
      <div class="icon">👥</div>
      <h3>Gestion des adhérents</h3>
      <p>Réservé à l’administration.</p>
    </div>
    <div class="service-card">
      <div class="icon">📝</div>
      <h3>Abonnements</h3>
      <p>Des formules simples, adaptées à tous.</p>
    </div>
    <div class="service-card">
      <div class="icon">📖</div>
      <h3>Catalogue</h3>
      <p>Accès à tous nos livres et supports numériques.</p>
      <a href="livres/lister.php">Voir les livres</a>
    </div>
  </div>
</section>

<footer class="footer">
  <div class="footer-container">
    <p>&copy; <?= date('Y') ?> La Maison du Livre — Tous droits réservés</p>
    <div class="footer-links">
      <a href="#">Mentions légales</a>
      <a href="#">Politique de confidentialité</a>
      <a href="#">Contact</a>
    </div>
  </div>
</footer>

</body>
</html>
