<?php
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $date = date('Y-m-d');

    $stmt = $pdo->prepare("INSERT INTO adherents (nom, email, date_inscription) VALUES (?, ?, ?)");
    $stmt->execute([$nom, $email, $date]);

    header('Location: lister.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Ajouter un adhérent</title></head>
<body>
<h1>Ajouter un adhérent</h1>
<form method="post">
    <label>Nom :</label><input type="text" name="nom" required><br>
    <label>Email :</label><input type="email" name="email" required><br>
    <button type="submit">Ajouter</button>
</form>
<a href="lister.php">Retour à la liste</a>
</body>
</html>
