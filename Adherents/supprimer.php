<?php
require_once '../includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM adherents WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: lister.php');
exit;
