<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$id_user = $_SESSION['user_id'];


$stmt = $pdo->prepare("SELECT email FROM utilisateurs WHERE id = ?");
$stmt->execute([$id_user]);
$user_info = $stmt->fetch();

if (!$user_info || empty($user_info['email'])) {
    echo "❌ Email introuvable.";
    exit;
}
$user_email = $user_info['email'];


$stmt = $pdo->prepare("SELECT ab.statut FROM utilisateurs u 
                       LEFT JOIN abonnements ab ON u.email = ab.email 
                       WHERE u.id = ?");
$stmt->execute([$id_user]);
$data = $stmt->fetch();

if (!$data || $data['statut'] !== 'actif') {
    echo "❌ Vous devez être abonné pour emprunter.";
    exit;
}

if (!isset($_GET['id']) || !isset($_GET['type'])) {
    echo "Support non spécifié.";
    exit;
}

$id_support = $_GET['id'];
$type = strtolower($_GET['type']);

$tables = [
    'livre' => 'livres',
    'dvd' => 'dvd',
    'cd' => 'cd',
    'magazine' => 'magazine'
];

if (!array_key_exists($type, $tables)) {
    echo "Type de support inconnu.";
    exit;
}

$table = $tables[$type];

$stmt = $pdo->prepare("SELECT * FROM $table WHERE id = ?");
$stmt->execute([$id_support]);
$support = $stmt->fetch();

if (!$support) {
    echo ucfirst($type) . " introuvable.";
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM prets 
    WHERE email_abonne = ? AND id_support = ? AND type_support = ? AND date_retour_reelle IS NULL");
$stmt->execute([$user_email, $id_support, $type]);
$pret_existant = $stmt->fetch();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$pret_existant) {
    $date_emprunt = date('Y-m-d');
    $date_retour = $_POST['date_retour'];

    $stmt = $pdo->prepare("INSERT INTO prets (email_abonne, id_support, type_support, date_emprunt, date_retour_prevue) 
                           VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_email, $id_support, $type, $date_emprunt, $date_retour]);

    $message = "✅ Emprunt effectué avec succès !";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Emprunter <?= htmlspecialchars($support['titre']) ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .fiche-support {
            max-width: 600px;
            margin: 50px auto;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .fiche-support img {
            max-width: 100%;
            margin-bottom: 15px;
        }

        .fiche-support h2 {
            margin-bottom: 10px;
        }

        .fiche-support form {
            margin-top: 20px;
        }

        .btn-group a {
            margin-right: 10px;
        }
    </style>
</head>
<body>
<div class="fiche-support">
    <h2><?= htmlspecialchars($support['titre']) ?></h2>

    <?php if (!empty($support['auteur'])): ?>
        <p><strong>Auteur :</strong> <?= htmlspecialchars($support['auteur']) ?></p>
    <?php elseif (!empty($support['realisateur'])): ?>
        <p><strong>Réalisateur :</strong> <?= htmlspecialchars($support['realisateur']) ?></p>
    <?php elseif (!empty($support['artiste'])): ?>
        <p><strong>Artiste :</strong> <?= htmlspecialchars($support['artiste']) ?></p>
    <?php endif; ?>

    <?php if (!empty($support['categorie'])): ?>
        <p><strong>Catégorie :</strong> <?= htmlspecialchars($support['categorie']) ?></p>
    <?php elseif (!empty($support['genre'])): ?>
        <p><strong>Genre :</strong> <?= htmlspecialchars($support['genre']) ?></p>
    <?php elseif (!empty($support['sujet'])): ?>
        <p><strong>Sujet :</strong> <?= htmlspecialchars($support['sujet']) ?></p>
    <?php endif; ?>

    <?php if (!empty($support['annee'])): ?>
        <p><strong>Année :</strong> <?= htmlspecialchars($support['annee']) ?></p>
    <?php elseif (!empty($support['date_publication'])): ?>
        <p><strong>Date de publication :</strong> <?= htmlspecialchars($support['date_publication']) ?></p>
    <?php endif; ?>

    <?php if (!empty($support['duree'])): ?>
        <p><strong>Durée :</strong> <?= htmlspecialchars($support['duree']) ?> min</p>
    <?php endif; ?>

    <?php if (!empty($support['image'])): ?>
        <img src="../<?= htmlspecialchars($support['image']) ?>" alt="Image du support">
    <?php endif; ?>

    <?php if ($pret_existant): ?>
        <p style="color:orange;">⏳ Vous avez déjà un emprunt en cours pour ce support.</p>
    <?php elseif ($message): ?>
        <p style="color:green;"><strong><?= $message ?></strong></p>
        <div class="btn-group">
            <a href="../index.php">🏠 Accueil</a>
            <a href="../profil.php">👤 Mon profil</a>
            <a href="../livres/lister.php">📚 Voir les supports</a>
        </div>
    <?php else: ?>
        <form method="post">
            <label>Date de retour prévue :</label><br>
            <input type="date" name="date_retour" required><br><br>
            <button type="submit">📖 Confirmer l'emprunt</button>
        </form>
        <div class="btn-group">
            <a href="../livres/lister.php">← Retour</a>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
