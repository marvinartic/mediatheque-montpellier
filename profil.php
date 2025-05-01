<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id_user = $_SESSION['user_id'];
$nom = $_SESSION['nom'];

$stmt = $pdo->prepare("
    SELECT u.email, u.role,
           ab.date_debut, ab.date_fin, ab.statut, ab.tarif
    FROM utilisateurs u
    LEFT JOIN abonnements ab ON u.email = ab.email
    WHERE u.id = ?
");
$stmt->execute([$id_user]);
$user_data = $stmt->fetch();

$role = $user_data['role'];
$user_email = $user_data['email'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil utilisateur</title>
    <link rel="stylesheet" href="css/profil.css">
</head>
<body>
<div class="profil-container">
    <h1>👋 Bienvenue, <?= htmlspecialchars($nom) ?> !</h1>

    <?php if ($role === 'admin'): ?>
        <h2>👑 Espace administrateur</h2>
        <ul>
            <li><a href="abonnements/gestion.php">Gestion des abonnements</a></li>
            <li><a href="adherents/lister.php">Gestion des adhérents</a></li>
            <li><a href="livres/lister.php">Gestion des livres / DVD / CD / Magazines</a></li>
            <li><a href="prets/historique.php">Historique des prêts</a></li>
        </ul>

    <?php else: ?>
        <h2>👤 Mon profil</h2>

        <?php if ($user_data['statut'] === 'actif'): ?>
            <p>Statut : Adhérent (abonné actif)</p>
            <p>Abonnement : <?= ucfirst($user_data['tarif']) ?></p>
            <p>Du <?= $user_data['date_debut'] ?> au <?= $user_data['date_fin'] ?></p>
            <a class="action-btn" href="livres/lister.php">📚 Découvrir mes avantages</a><br>
            <a class="action-btn" style="background-color: #ffc107; color: #333;" href="abonnement.php">📝 Gérer mon abonnement</a>

        <?php elseif ($user_data['statut'] === 'résilié'): ?>
            <p style="color:darkorange;"><strong>Votre abonnement a été résilié.</strong></p>
            <p><strong>Ancienne formule :</strong> <?= ucfirst($user_data['tarif']) ?></p>
            <p>Période : <?= $user_data['date_debut'] ?> → <?= $user_data['date_fin'] ?></p>
            <a class="action-btn" style="background-color: #007bff;" href="abonnement.php">🔁 Se réabonner</a>

        <?php else: ?>
            <p>Statut : Client (non abonné)</p>
            <a class="action-btn" href="abonnement.php">🔓 Activer mon abonnement</a>
        <?php endif; ?>

        <h3 style="margin-top: 30px;">📘 Mon historique d’emprunts</h3>

        <?php
        $types = [
            'livres' => ['table' => 'livres', 'label' => '📘 Livre'],
            'dvd' => ['table' => 'dvd', 'label' => '📀 DVD'],
            'cd' => ['table' => 'cd', 'label' => '🎵 CD'],
            'magazine' => ['table' => 'magazine', 'label' => '📰 Magazine'],
        ];

        $has_emprunts = false;

        foreach ($types as $key => $info) {
            $stmt = $pdo->prepare("
                SELECT p.*, s.titre 
                FROM prets p
                JOIN {$info['table']} s ON p.id_support = s.id
                WHERE p.email_abonne = ?
                ORDER BY p.date_emprunt DESC
            ");
            $stmt->execute([$user_email]);
            $prets = $stmt->fetchAll();

            if ($prets) {
                $has_emprunts = true;
                echo "<h4 style='margin-top:20px;'>{$info['label']}s</h4>";
                foreach ($prets as $pret) {
                    $statut = $pret['statut'];
                    $badge = $statut === 'rendu' ? '✅ Rendu' : ($statut === 'retard' ? '❌ En retard' : '🕓 En cours');
                    $couleur = $statut === 'rendu' ? 'green' : ($statut === 'retard' ? 'red' : '#ffc107');
                    ?>
                    <div style="background:#f8f8f8; border-radius:8px; padding:15px; margin:15px 0;">
                        <p><strong><?= $info['label'] ?> :</strong> <?= htmlspecialchars($pret['titre']) ?></p>
                        <p><strong>Emprunté le :</strong> <?= $pret['date_emprunt'] ?> | <strong>Retour prévu :</strong> <?= $pret['date_retour_prevue'] ?></p>
                        <p><strong>Retour réel :</strong> <?= $pret['date_retour_reelle'] ?: '—' ?></p>
                        <p><strong>Statut :</strong> <span style="color:<?= $couleur ?>; font-weight:bold;"><?= $badge ?></span></p>

                        <?php if ($pret['statut'] === 'en cours' && !$pret['date_retour_reelle']): ?>
                            <form method="post" action="rendre.php">
                                <input type="hidden" name="pret_id" value="<?= $pret['id'] ?>">
                                <button type="submit" class="action-btn" style="background:#dc3545;">📤 Rendre ce support</button>
                            </form>
                        <?php endif; ?>
                    </div>
                    <?php
                }
            }
        }

        if (!$has_emprunts) {
            echo "<p>Aucun emprunt pour le moment.</p>";
        }
        ?>
    <?php endif; ?>

    <br><br>
    <a href="index.php">← Retour à l’accueil</a> |
    <a href="logout.php">Se déconnecter</a>
</div>
</body>
</html>
