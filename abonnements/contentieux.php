<?php
require_once '../includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("UPDATE abonnements SET statut = 'contentieux' WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: gestion.php');
exit;
