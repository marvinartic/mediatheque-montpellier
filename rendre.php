<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id_user = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pret_id'])) {
    $pret_id = $_POST['pret_id'];

    $stmt = $pdo->prepare("SELECT email FROM utilisateurs WHERE id = ?");
    $stmt->execute([$id_user]);
    $email = $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT * FROM prets WHERE id = ? AND email_abonne = ?");
    $stmt->execute([$pret_id, $email]);
    $pret = $stmt->fetch();

    if ($pret) {
        $stmt = $pdo->prepare("UPDATE prets SET date_retour_reelle = CURDATE(), statut = 'rendu' WHERE id = ?");
        $stmt->execute([$pret_id]);

        header("Location: profil.php");
        exit;
    } else {
        echo "⛔ Ce prêt ne vous appartient pas.";
    }
} else {
    echo "⛔ Requête invalide.";
}
