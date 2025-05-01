<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id_user = $_SESSION['user_id'];
$nom = $_SESSION['nom'];

$stmt = $pdo->prepare("SELECT ab.statut FROM utilisateurs u 
                       LEFT JOIN abonnements ab ON u.email = ab.email 
                       WHERE u.id = ?");
$stmt->execute([$id_user]);
$user_data = $stmt->fetch();

$is_abonne = $user_data && $user_data['statut'] === 'actif';

if (!$is_abonne) {
    header('Location: profil.php');
    exit;
}

$livres = $pdo->query("SELECT id, titre, auteur FROM livres")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes avantages</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div style="max-width: 800px; margin: 50px auto; padding: 20px;">
    <h2>👋 Bonjour <?= htmlspecialchars($nom) ?>, bienvenue dans votre espace abonné</h2>
    <p>Voici les livres que vous pouvez emprunter :</p>

    <div class="cards">
        <?php foreach ($livres as $livre): ?>
            <div class="card">
                <h3><?= htmlspecialchars($livre['titre']) ?></h3>
                <p>Auteur : <?= htmlspecialchars($livre['auteur']) ?></p>
                <a href="prets/emprunter.php?id=<?= $livre['id'] ?>">📖 Emprunter</a>
            </div>
        <?php endforeach; ?>
    </div>

    <p><a href="profil.php" style="color: #28a745;">← Retour à mon profil</a></p>
</div>
</body>
</html>
