<?php
require_once '../includes/db.php';

$id = $_GET['id'];
$livre = $pdo->prepare("SELECT * FROM livres WHERE id = ?");
$livre->execute([$id]);
$l = $livre->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $auteur = $_POST['auteur'];
    $categorie = $_POST['categorie'];
    $disponible = isset($_POST['disponible']) ? 1 : 0;

    $stmt = $pdo->prepare("UPDATE livres SET titre = ?, auteur = ?, categorie = ?, disponible = ? WHERE id = ?");
    $stmt->execute([$titre, $auteur, $categorie, $disponible, $id]);

    header('Location: lister.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Modifier livre</title></head>
<body>
<h1>Modifier un livre</h1>
<form method="post">
    <label>Titre :</label><input type="text" name="titre" value="<?= htmlspecialchars($l['titre']) ?>" required><br>
    <label>Auteur :</label><input type="text" name="auteur" value="<?= htmlspecialchars($l['auteur']) ?>" required><br>
    <label>Catégorie :</label><input type="text" name="categorie" value="<?= htmlspecialchars($l['categorie']) ?>"><br>
    <label>Disponible :</label><input type="checkbox" name="disponible" <?= $l['disponible'] ? 'checked' : '' ?>><br>
    <button type="submit">Mettre à jour</button>
</form>
<a href="lister.php">Retour</a>
</body>
</html>
