<?php
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $date_debut = date('Y-m-d');
    $date_fin = date('Y-m-d', strtotime('+1 year'));

    $stmt = $pdo->prepare("INSERT INTO abonnements (nom, email, date_debut, date_fin) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nom, $email, $date_debut, $date_fin]);

    header('Location: gestion.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un abonnement</title>
</head>
<body>
<h1>Ajouter un nouvel abonnement</h1>
<form method="post">
    <label for="nom">Nom :</label>
    <input type="text" name="nom" id="nom" required><br>

    <label for="email">Email :</label>
    <input type="email" name="email" id="email" required><br>

    <button type="submit">Ajouter</button>
</form>
<a href="gestion.php">Retour à la gestion</a>
</body>
</html>
