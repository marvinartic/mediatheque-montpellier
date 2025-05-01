<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id_user = $_SESSION['user_id'];


$stmt = $pdo->prepare("SELECT u.email, a.date_naissance FROM utilisateurs u JOIN adherents a ON u.id_adherent = a.id WHERE u.id = ?");
$stmt->execute([$id_user]);
$user = $stmt->fetch();

if (!$user) {
    echo "Erreur : utilisateur non trouvé.";
    exit;
}

$email = $user['email'];
$date_naissance = $user['date_naissance'];
$today = new DateTime();

if ($date_naissance) {
    $birthdate = new DateTime($date_naissance);
    $age = $today->diff($birthdate)->y;
} else {
    $age = null;
}


$stmt = $pdo->prepare("SELECT * FROM abonnements WHERE email = ?");
$stmt->execute([$email]);
$abonnement = $stmt->fetch();


if (isset($_POST['deposer_justificatif']) && isset($_FILES['justificatif'])) {
    $upload_dir = __DIR__ . '/uploads/justificatifs/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $file = $_FILES['justificatif'];
    $file_name = uniqid() . '_' . basename($file['name']);
    $target_file = $upload_dir . $file_name;

    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        
        $stmt = $pdo->prepare("INSERT INTO demandes_abonnement (email, fichier_justificatif, statut) VALUES (?, ?, 'en attente')");
        $stmt->execute([$email, $file_name]);
        $message = "✅ Justificatif déposé avec succès. Votre demande est en attente de validation.";
    } else {
        $message = "❌ Erreur lors du téléchargement du fichier.";
    }
}


$stmt = $pdo->prepare("SELECT * FROM demandes_abonnement WHERE email = ? ORDER BY id DESC LIMIT 1");
$stmt->execute([$email]);
$demande = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon abonnement</title>
    <link rel="stylesheet" href="css/abonnement.css">
</head>
<body>
    <div class="container">
        <h1>🎟️ Mon abonnement</h1>

        <?php if (isset($message)): ?>
            <p><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <?php if ($abonnement && $abonnement['statut'] === 'actif'): ?>
            <p><strong>Email :</strong> <?= htmlspecialchars($email) ?></p>
            <p><strong>Formule :</strong> <?= ucfirst($abonnement['tarif']) ?></p>
            <p><strong>Du :</strong> <?= $abonnement['date_debut'] ?> → <?= $abonnement['date_fin'] ?></p>

            <form method="post" style="margin-top: 20px;">
                <button type="submit" name="resilier" class="action-btn cancel">❌ Résilier mon abonnement</button>
            </form>

        <?php elseif ($abonnement && $abonnement['statut'] === 'résilié'): ?>
            <p style="color:darkorange;">Votre abonnement est résilié.</p>
            <form method="post" style="margin-top: 20px;">
                <button type="submit" name="souscrire" class="action-btn">🔁 Se réabonner</button>
            </form>

        <?php else: ?>
            <p><strong>Âge :</strong> <?= $age !== null ? $age . ' ans' : 'Non renseigné' ?></p>

            <?php if ($age !== null && $age < 18): ?>
                <?php if ($demande): ?>
                    <p><strong>Statut de la demande :</strong> <?= ucfirst($demande['statut']) ?></p>
                <?php else: ?>
                    <p>🎉 Aucun abonnement actif actuellement.</p>
                    <p><strong>Offre disponible :</strong> Abonnement gratuit pour les moins de 18 ans.</p>
                    <form method="post" enctype="multipart/form-data" style="margin-top: 20px;">
                        <label for="justificatif">Déposer un justificatif d'identité :</label><br>
                        <input type="file" name="justificatif" id="justificatif" required><br><br>
                        <button type="submit" name="deposer_justificatif" class="action-btn">Soumettre la demande</button>
                    </form>
                <?php endif; ?>
            <?php else: ?>
                <p>🎉 Aucun abonnement actif actuellement.</p>
                <p><strong>Offre disponible :</strong> Abonnement payant.</p>
                <form method="post" style="margin-top: 20px;">
                    <button type="submit" name="souscrire" class="action-btn">Souscrire maintenant</button>
                </form>
            <?php endif; ?>
        <?php endif; ?>

        <br><br>
        <a href="profil.php" class="return">← Retour au profil</a>
    </div>
</body>
</html>
