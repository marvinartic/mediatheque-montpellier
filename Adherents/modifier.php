<?php
require_once '../includes/db.php';

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM adherents WHERE id = ?");
$stmt->execute([$id]);
$adherent = $stmt->fetch();

$stmt = $pdo->prepare("SELECT statut FROM abonnements WHERE email = ?");
$stmt->execute([$adherent['email']]);
$abonnement = $stmt->fetch();

$statut_abonnement = $abonnement['statut'] ?? 'inactif';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $statut = $_POST['statut'];

    $stmt = $pdo->prepare("UPDATE adherents SET nom = ?, email = ? WHERE id = ?");
    $stmt->execute([$nom, $email, $id]);

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM abonnements WHERE email = ?");
    $stmt->execute([$email]);
    $existe = $stmt->fetchColumn() > 0;

    if ($existe) {
        $stmt = $pdo->prepare("UPDATE abonnements SET statut = ? WHERE email = ?");
        $stmt->execute([$statut, $email]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO abonnements (email, statut, date_debut, date_fin, tarif) 
                               VALUES (?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 YEAR), 'gratuit')");
        $stmt->execute([$email, $statut]);
    }

    header('Location: lister.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Modifier adhérent</title>
</head>
<body>
<h1>Modifier adhérent</h1>

<form method="post">
    <label>Nom :</label>
    <input type="text" name="nom" value="<?= htmlspecialchars($adherent['nom']) ?>" required><br>

    <label>Email :</label>
    <input type="email" name="email" value="<?= htmlspecialchars($adherent['email']) ?>" required><br>

    <label>Statut :</label>
    <select name="statut">
        <option value="actif" <?= $statut_abonnement === 'actif' ? 'selected' : '' ?>>Actif</option>
        <option value="inactif" <?= $statut_abonnement === 'inactif' ? 'selected' : '' ?>>Inactif</option>
    </select><br>

    <button type="submit">Mettre à jour</button>
</form>

<a href="lister.php">← Retour</a>
</body>
</html>
