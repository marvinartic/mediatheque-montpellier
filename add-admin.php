<?php
require_once 'includes/db.php';

$nom = 'Admin';
$email = 'marvinartic@gmail.com';
$mdp = 'a';
$hash = password_hash($mdp, PASSWORD_DEFAULT);
$role = 'admin';

$stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)");
$stmt->execute([$nom, $email, $hash, $role]);

echo "✔️ Compte administrateur créé avec succès.";
?>
