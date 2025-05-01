<?php
require_once '../includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("UPDATE abonnements SET date_fin = DATE_ADD(date_fin, INTERVAL 1 YEAR), statut = 'actif' WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: gestion.php');
exit;
