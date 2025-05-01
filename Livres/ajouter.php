<?php
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $auteur = $_POST['auteur'];
    $categorie = $_POST['categorie'];

    $stmt = $pdo->prepare("INSERT INTO livres (titre, auteur, categorie) VALUES (?, ?, ?)");
    $stmt->execute([$titre, $auteur, $categorie]);

    header('Location: lister.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Ajouter un livre</title></head>
<body>
<h1>Ajouter un livre</h1>
<form method="post">
    <label>Titre :</label><input type="text" name="titre" required><br>
    <label>Auteur :</label><input type="text" name="auteur" required><br>
    <label>Catégorie :</label><input type="text" name="categorie"><br>
    <button type="submit">Ajouter</button>
</form>
<a href="lister.php">Retour</a>
</body>
</html>
