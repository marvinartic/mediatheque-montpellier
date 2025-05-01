<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../includes/db.php';


$livres = $pdo->query("SELECT *, 'Livre' AS type FROM livres")->fetchAll();
$dvds = $pdo->query("SELECT *, 'DVD' AS type FROM dvd")->fetchAll();
$cds = $pdo->query("SELECT *, 'CD' AS type FROM cd")->fetchAll();
$magazines = $pdo->query("SELECT *, 'Magazine' AS type FROM magazine")->fetchAll();

$supports = array_merge($livres, $dvds, $cds, $magazines);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Catalogue multimédia</title>
    <link rel="stylesheet" href="../css/lister.css">
</head>
<body>

<div class="profil-container">
    <h1>📚 Catalogue de la médiathèque</h1>

    <div style="text-align: center; margin-bottom: 25px;">
        <a href="../index.php" class="action-btn">🏠 Accueil</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="../profil.php" class="action-btn">👤 Mon profil</a>
        <?php endif; ?>
    </div>

    <?php if (empty($supports)): ?>
        <p style="text-align: center;">Aucun support disponible pour le moment.</p>
    <?php else: ?>
        <div class="cards">
            <?php foreach ($supports as $item): ?>
                <?php
                // Vérifie si ce support est déjà emprunté
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM prets 
                    WHERE id_support = ? AND type_support = ? AND date_retour_reelle IS NULL");
                $stmt->execute([$item['id'], strtolower($item['type'])]);
                $est_emprunte = $stmt->fetchColumn() > 0;
                ?>

                <div class="card">
                    <?php if (!empty($item['image'])): ?>
                        <img src="../<?= htmlspecialchars($item['image']) ?>" alt="Couverture" style="width:150px; border-radius:10px; margin-bottom:10px;">
                    <?php else: ?>
                        <p style="color:red;">Aucune image fournie</p>
                    <?php endif; ?>

                    <h3><?= htmlspecialchars($item['titre']) ?></h3>
                    <p><strong>Type :</strong> <?= htmlspecialchars($item['type']) ?></p>

                    <?php if (!empty($item['auteur'])): ?>
                        <p><strong>Auteur :</strong> <?= htmlspecialchars($item['auteur']) ?></p>
                    <?php elseif (!empty($item['realisateur'])): ?>
                        <p><strong>Réalisateur :</strong> <?= htmlspecialchars($item['realisateur']) ?></p>
                    <?php elseif (!empty($item['artiste'])): ?>
                        <p><strong>Artiste :</strong> <?= htmlspecialchars($item['artiste']) ?></p>
                    <?php endif; ?>

                    <?php if (!empty($item['categorie'])): ?>
                        <p><strong>Catégorie :</strong> <?= htmlspecialchars($item['categorie']) ?></p>
                    <?php elseif (!empty($item['genre'])): ?>
                        <p><strong>Genre :</strong> <?= htmlspecialchars($item['genre']) ?></p>
                    <?php elseif (!empty($item['sujet'])): ?>
                        <p><strong>Sujet :</strong> <?= htmlspecialchars($item['sujet']) ?></p>
                    <?php endif; ?>

                    <p><strong>Disponible :</strong> <?= $est_emprunte ? 'Non' : 'Oui' ?></p>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($est_emprunte): ?>
                            <a href="emprunter.php?id=<?= $item['id'] ?>&type=<?= strtolower($item['type']) ?>" class="action-btn" style="background-color: gray;">
                                👁 Voir
                            </a>
                        <?php else: ?>
                            <a href="emprunter.php?id=<?= $item['id'] ?>&type=<?= strtolower($item['type']) ?>" class="action-btn">
                                📚 Emprunter
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="../login.php" class="action-btn" style="background-color: #c0392b;">
                            🔒 Se connecter pour emprunter
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<footer class="main-footer">
    <div class="footer-content">
        <p>&copy; <?= date('Y') ?> La Maison du Livre. Tous droits réservés.</p>
        <div class="footer-links">
            <a href="#">Mentions légales</a>
            <a href="#">Politique de confidentialité</a>
            <a href="#">Contact</a>
        </div>
    </div>
</footer>

</body>
</html>
