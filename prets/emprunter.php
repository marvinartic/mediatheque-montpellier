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
$user_email = $user_info['email'];


$stmt = $pdo->prepare("SELECT u.role, a.statut FROM utilisateurs u 
    LEFT JOIN abonnements a ON u.email = a.email 
    WHERE u.id = ?");
$stmt->execute([$id_user]);
$data = $stmt->fetch();

if (!$data || ($data['role'] !== 'admin' && $data['statut'] !== 'actif')) {
    echo "<p style='color:red;'>❌ Vous devez être abonné pour emprunter un livre.</p>";
    exit;
}


if (!isset($_GET['id'])) {
    echo "Livre non spécifié.";
    exit;
}

$id_livre = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM livres WHERE id = ?");
$stmt->execute([$id_livre]);
$livre = $stmt->fetch();

if (!$livre) {
    echo "Livre introuvable.";
    exit;
}


$stmt = $pdo->prepare("SELECT * FROM prets 
    WHERE email_abonne = ? AND id_livre = ? AND date_retour_reelle IS NULL");
$stmt->execute([$user_email, $id_livre]);
$pret_existant = $stmt->fetch();


$message = '';
$emprunt_reussi = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$pret_existant) {
    $date_emprunt = date('Y-m-d');
    $date_retour = $_POST['date_retour'];

    $stmt = $pdo->prepare("INSERT INTO prets (email_abonne, id_livre, date_emprunt, date_retour_prevue) 
                           VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_email, $id_livre, $date_emprunt, $date_retour]);

    $message = "✅ Livre emprunté avec succès !";
    $emprunt_reussi = true;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Emprunter <?= htmlspecialchars($livre['titre']) ?></title>
    <link rel="stylesheet" href="../css/emprunter.css">
</head>
<body>
<div class="fiche-livre">
    <h2><?= htmlspecialchars($livre['titre']) ?></h2>
    <p><strong>Auteur :</strong> <?= htmlspecialchars($livre['auteur']) ?></p>
    <p><strong>Catégorie :</strong> <?= htmlspecialchars($livre['categorie']) ?></p>
    
    <?php if (!empty($livre['image'])): ?>
        <img src="<?= htmlspecialchars($livre['image']) ?>" alt="Couverture du livre">
    <?php endif; ?>

    <p><?= nl2br(htmlspecialchars($livre['description'])) ?></p>

    <?php if ($pret_existant): ?>
        <p style="color:orange;">⏳ Vous avez déjà un emprunt en cours pour ce livre.</p>
    <?php elseif ($emprunt_reussi): ?>
        <p class="success"><?= $message ?></p>
        <div class="btn-group">
            <a href="../index.php">🏠 Accueil</a>
            <a href="../profil.php">👤 Mon profil</a>
            <a href="../livres/lister.php">📚 Voir d'autres livres</a>
        </div>
    <?php else: ?>
        <form method="post">
            <label>Date de retour souhaitée :</label><br>
            <input type="date" name="date_retour" required><br><br>
            <button type="submit">📖 Confirmer l'emprunt</button>
        </form>
        <div class="btn-group">
    <a href="../livres/lister.php">← Retour aux livres</a>
</div>
    <?php endif; ?>
</div>
</body>
</html>
