<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['user_id']) || (isset($_SESSION['user_role']) && $_SESSION['user_role'] !== 'admin')) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $email = $_POST['email'];
    $action = $_POST['action'];

    if ($action === 'accepter') {
        $stmt = $pdo->prepare("UPDATE demandes_abonnement SET statut = 'accepté' WHERE id = ?");
        $stmt->execute([$id]);

        $date_debut = date('Y-m-d');
        $date_fin = date('Y-m-d', strtotime('+1 year'));

        $stmt = $pdo->prepare("SELECT id FROM abonnements WHERE email = ?");
        $stmt->execute([$email]);
        $abonnement = $stmt->fetch();

        if ($abonnement) {
            $stmt = $pdo->prepare("UPDATE abonnements SET date_debut = ?, date_fin = ?, statut = 'actif', tarif = 'gratuit' WHERE email = ?");
            $stmt->execute([$date_debut, $date_fin, $email]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO abonnements (email, date_debut, date_fin, statut, tarif) VALUES (?, ?, ?, 'actif', 'gratuit')");
            $stmt->execute([$email, $date_debut, $date_fin]);
        }

    } elseif ($action === 'refuser') {
        $stmt = $pdo->prepare("UPDATE demandes_abonnement SET statut = 'refusé' WHERE id = ?");
        $stmt->execute([$id]);
    }
}

$stmt = $pdo->query("SELECT da.id, da.email, da.fichier_justificatif, da.date_demande, u.nom, u.prenom FROM demandes_abonnement da JOIN utilisateurs u ON da.email = u.email WHERE da.statut = 'en attente'");
$demandes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des abonnements</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <h1>📂 Gestion des demandes d'abonnement</h1>
    <table>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Justificatif</th>
            <th>Date de demande</th>
            <th>Action</th>
        </tr>
        <?php foreach ($demandes as $demande): ?>
        <tr>
            <td><?= htmlspecialchars($demande['nom']) ?></td>
            <td><?= htmlspecialchars($demande['prenom']) ?></td>
            <td><?= htmlspecialchars($demande['email']) ?></td>
            <td><a href="../uploads/justificatifs/<?= htmlspecialchars($demande['fichier_justificatif']) ?>" target="_blank">📄 Voir</a></td>
            <td><?= $demande['date_demande'] ?></td>
            <td>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $demande['id'] ?>">
                    <input type="hidden" name="email" value="<?= htmlspecialchars($demande['email']) ?>">
                    <button type="submit" name="action" value="accepter">✅ Accepter</button>
                    <button type="submit" name="action" value="refuser" style="margin-left:10px;">❌ Refuser</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <br>
    <a href="../profil.php">← Retour au profil admin</a>
</body>
</html>
