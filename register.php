<?php
require_once 'includes/db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $date_naissance = $_POST['date_naissance'];
    $mdp = $_POST['mot_de_passe'];
    $hash = password_hash($mdp, PASSWORD_DEFAULT);
    $date_inscription = date('Y-m-d');
    $role = 'client';

    $check_user = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
    $check_user->execute([$email]);

    if ($check_user->fetch()) {
        $message = "❌ Cet email est déjà utilisé. Veuillez vous connecter.";
    } else {
        try {
            $check = $pdo->prepare("SELECT id FROM adherents WHERE email = ?");
            $check->execute([$email]);
            $exist = $check->fetch();

            if ($exist) {
                $id_adherent = $exist['id'];
            } else {
                $stmt1 = $pdo->prepare("INSERT INTO adherents (nom, prenom, email, date_inscription, date_naissance) VALUES (?, ?, ?, ?, ?)");
                $stmt1->execute([$nom, $prenom, $email, $date_inscription, $date_naissance]);
                $id_adherent = $pdo->lastInsertId();
            }

            $stmt2 = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, id_adherent) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt2->execute([$nom, $prenom, $email, $hash, $role, $id_adherent]);

            $message = "✅ Inscription réussie. Vous pouvez maintenant vous connecter.";
        } catch (PDOException $e) {
            $message = "Erreur : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>

<form method="post">
    <h1>Créer un compte</h1>
    <h2 class="subtitle">Rejoignez notre communauté de lecteurs passionnés</h2>

    <?php if ($message): ?><p><?= htmlspecialchars($message) ?></p><?php endif; ?>

    <input type="text" name="nom" placeholder="Nom*" required>
    <input type="text" name="prenom" placeholder="Prénom*" required>
    <input type="email" name="email" placeholder="E-mail*" required>
    <input type="date" name="date_naissance" required>
    <input type="password" name="mot_de_passe" placeholder="Mot de passe*" required>

    <button type="submit">S’inscrire</button>

    <div class="form-footer">
        <a href="index.php">← Retour à l’accueil</a>
        <a href="login.php">Déjà inscrit ? Se connecter</a>
    </div>
</form>

</body>
</html>
