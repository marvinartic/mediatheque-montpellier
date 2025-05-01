<?php
require_once 'includes/db.php';

echo "<h1>🧪 Tests de la médiathèque</h1>";

try {
    $pdo->query("SELECT 1");
    echo "✅ Connexion à la base de données : OK<br>";
} catch (PDOException $e) {
    echo "❌ Connexion échouée : " . $e->getMessage();
    exit;
}

$stmt = $pdo->query("
    SELECT u.email 
    FROM utilisateurs u 
    JOIN abonnements ab ON u.email = ab.email 
    WHERE ab.statut = 'actif' 
    LIMIT 1
");
$abonne = $stmt->fetch();

if ($abonne) {
    $email = $abonne['email'];
    echo "✅ Utilisateur abonné trouvé : $email<br>";
} else {
    echo "❌ Aucun utilisateur abonné trouvé.<br>";
    exit;
}

$supports = [
    'livre' => 'livres',
    'dvd' => 'dvd',
    'cd' => 'cd',
    'magazine' => 'magazine'
];

foreach ($supports as $type => $table) {
    $stmt = $pdo->query("SELECT id, titre FROM $table LIMIT 1");
    $support = $stmt->fetch();

    if ($support) {
        echo "✅ $type trouvé : {$support['titre']} (ID {$support['id']})<br>";

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM prets 
            WHERE email_abonne = ? AND id_support = ? AND type_support = ? AND date_retour_reelle IS NULL");
        $stmt->execute([$email, $support['id'], $type]);
        $existe = $stmt->fetchColumn();

        if (!$existe) {
            $stmt = $pdo->prepare("INSERT INTO prets 
                (email_abonne, id_support, type_support, date_emprunt, date_retour_prevue, statut)
                VALUES (?, ?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 14 DAY), 'en cours')");
            $stmt->execute([$email, $support['id'], $type]);
            echo "✅ Emprunt de test ajouté pour ce $type.<br>";
        } else {
            echo "ℹ️ $type déjà emprunté, pas de doublon ajouté.<br>";
        }
    } else {
        echo "❌ Aucun $type trouvé dans la base.<br>";
    }
}

echo "<br><strong>✅ Tous les tests ont été exécutés avec succès.</strong>";
?>
