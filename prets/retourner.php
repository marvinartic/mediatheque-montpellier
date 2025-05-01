<?php
require_once '../includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("UPDATE prets SET date_retour = CURDATE() WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: historique.php');
exit;
