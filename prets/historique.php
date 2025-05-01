<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}


$stmt = $pdo->prepare("SELECT role FROM utilisateurs WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$role = $stmt->fetchColumn();

if ($role !== 'admin') {
    echo "⛔️ Accès réservé à l'administrateur.";
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pret_id'])) {
    $id_pret = $_POST['pret_id'];
    $stmt = $pdo->prepare("UPDATE prets SET date_retour_reelle = CURDATE(), statut = 'rendu' WHERE id = ?");
    $stmt->execute([$id_pret]);
    header("Location: historique.php");
    exit;
}


$today = date('Y-m-d');
$pdo->query("UPDATE prets SET statut = 'retard' 
             WHERE statut = 'en cours' AND date_retour_prevue < '$today' AND date_retour_reelle IS NULL");

$prets = [];

$types = [
    'livre' => ['table' => 'livres', 'label' => '📘 Livre'],
    'dvd' => ['table' => 'dvd', 'label' => '📀 DVD'],
    'cd' => ['table' => 'cd', 'label' => '🎵 CD'],
    'magazine' => ['table' => 'magazine', 'label' => '📰 Magazine']
];

foreach ($types as $type => $info) {
    $stmt = $pdo->prepare("
        SELECT p.*, u.email, s.titre
        FROM prets p
        JOIN {$info['table']} s ON p.id_support = s.id
        JOIN utilisateurs u ON p.email_abonne = u.email
        WHERE p.type_support = ?
        ORDER BY p.date_emprunt DESC
    ");
    $stmt->execute([$type]);
    $prets = array_merge($prets, $stmt->fetchAll());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des prêts</title>
    <link rel="stylesheet" href="../css/historique.css">
</head>
<body>
<div class="container">
    <h1>📚 Historique des prêts</h1>

    <?php foreach ($prets as $pret): 
        $statut = $pret['statut'];
        $class = $statut === 'rendu' ? 'rendu' : ($statut === 'retard' ? 'retard' : 'en-cours');
        $statut_label = $statut === 'rendu' ? '✅ Rendu' : ($statut === 'retard' ? '❌ En retard' : '🕓 En cours');
    ?>
    <div class="pret-card">
        <p><strong>Support :</strong> <?= htmlspecialchars($pret['titre']) ?> (<?= strtoupper($pret['type_support']) ?>)</p>
        <p><strong>Utilisateur :</strong> <?= htmlspecialchars($pret['email']) ?></p>
        <p><strong>Date emprunt :</strong> <?= $pret['date_emprunt'] ?></p>
        <p><strong>Retour prévu :</strong> <?= $pret['date_retour_prevue'] ?></p>
        <p><strong>Retour réel :</strong> <?= $pret['date_retour_reelle'] ?: '—' ?></p>
        <p class="statut <?= $class ?>"><?= $statut_label ?></p>

        <?php if (!$pret['date_retour_reelle']): ?>
            <form method="post" style="margin-top: 15px;">
                <input type="hidden" name="pret_id" value="<?= $pret['id'] ?>">
                <button type="submit" class="action-btn" style="padding: 8px 14px;">📦 Marquer comme rendu</button>
            </form>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>

    <a class="return" href="../profil.php">← Retour au profil</a>
</div>

</body>
</html>
