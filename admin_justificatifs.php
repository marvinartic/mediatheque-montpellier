<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if (isset($_POST['accepter'])) {
    $id = $_POST['id'];
    $email = $_POST['email'];

    $pdo->prepare("UPDATE demandes_abonnement SET statut = 'accepté' WHERE id = ?")->execute([$id]);

    $date_debut = date('Y-m-d');
    $date_fin = date('Y-m-d', strtotime('+1 year'));
    $tarif = 'gratuit';

    $stmt = $pdo->prepare("SELECT id FROM abonnements WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $pdo->prepare("UPDATE abonnements SET date_debut = ?, date_fin = ?, statut = 'actif', tarif = ? WHERE email = ?")
            ->execute([$date_debut, $date_fin, $tarif, $email]);
    } else {
        $pdo->prepare("INSERT INTO abonnements (email, date_debut, date_fin, statut, tarif) VALUES (?, ?, ?, 'actif', ?)")
            ->execute([$email, $date_debut, $date_fin, $tarif]);
    }
}

if (isset($_POST['refuser'])) {
    $id = $_POST['id'];
    $pdo->prepare("UPDATE demandes_abonnement SET statut = 'refusé' WHERE id = ?")->execute([$id]);
}

$demandes = $pdo->query("SELECT * FROM demandes_abonnement WHERE statut = 'en attente'")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des demandes d'abonnement</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <h1>Demandes d'abonnement en attente</h1>
    <table>
        <tr>
            <th>Email</th>
            <th>Justificatif</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($demandes as $demande): ?>
            <tr>
                <td><?= htmlspecialchars($demande['email']) ?></td>
                <td><a href="uploads/justificatifs/<?= htmlspecialchars($demande['fichier_justificatif']) ?>" target="_blank">Voir</a></td>
                <td><?= htmlspecialchars($demande['date_demande']) ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $demande['id'] ?>">
                        <input type="hidden" name="email" value="<?= $demande['email'] ?>">
                        <button type="submit" name="accepter">Accepter</button>
                    </form>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $demande['id'] ?>">
                        <button type="submit" name="refuser">Refuser</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
