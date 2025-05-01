<?php
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $date_naissance = $_POST['date_naissance'] ?? null;
    $date_inscription = date('Y-m-d');

    $stmt = $pdo->prepare("INSERT INTO adherents (nom, email, date_naissance, date_inscription) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nom, $email, $date_naissance, $date_inscription]);

    header('Location: lister.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ajouter un adhérent</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            padding: 20px;
        }

        h1 {
            color: #2833a7;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input {
            padding: 8px;
            width: 250px;
        }

        button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #2833a7;
            color: white;
            border: none;
            cursor: pointer;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            color: #2833a7;
        }
    </style>
</head>
<body>
<h1>Ajouter un adhérent</h1>
<form method="post">
    <label>Nom :</label>
    <input type="text" name="nom" required>

    <label>Email :</label>
    <input type="email" name="email" required>

    <label>Date de naissance :</label>
    <input type="date" name="date_naissance">

    <button type="submit">Ajouter</button>
</form>

<a href="lister.php">← Retour à la liste</a>
</body>
</html>
