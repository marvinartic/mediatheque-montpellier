<?php
require_once '../includes/db.php';

function calculerAge($date_naissance) {
    if (empty($date_naissance)) {
        return '—'; // ou retourne 0 si tu veux un chiffre
    }
    try {
        $date_naissance = new DateTime($date_naissance);
        $aujourdhui = new DateTime();
        $age = $aujourdhui->diff($date_naissance)->y;
        return $age;
    } catch (Exception $e) {
        return '—';
    }
}

$stmt = $pdo->query("
    SELECT a.id, a.nom, a.email, a.date_naissance, a.date_inscription,
           ab.statut AS statut_abonnement, ab.tarif
    FROM adherents a
    LEFT JOIN abonnements ab ON a.email = ab.email
    ORDER BY a.date_inscription DESC
");
$adherents = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Liste des adhérents</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #2833a7;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }

        th, td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #e5e5e5;
        }

        th {
            background-color: #2833a7;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        a {
            color: #2833a7;
            text-decoration: none;
            font-weight: 500;
        }

        a:hover {
            text-decoration: underline;
        }

        .statut-actif {
            color: green;
            font-weight: bold;
        }

        .statut-non {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Liste des adhérents</h1>
    <a href="ajouter.php">Ajouter un adhérent</a>
    <table>
        <tr>
            <th>Nom</th>
            <th>Email</th>
            <th>Âge</th>
            <th>Inscription</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($adherents as $a): ?>
            <?php
                $age = calculerAge($a['date_naissance']);
                $statut = 'Non';
                $classe_statut = 'statut-non';

                if (is_numeric($age) && $age < 18) {
                    if ($a['statut_abonnement'] === 'actif') {
                        $statut = 'Actif';
                        $classe_statut = 'statut-actif';
                    }
                } elseif (is_numeric($age)) {
                    if ($a['statut_abonnement'] === 'actif') {
                        $statut = 'Actif';
                        $classe_statut = 'statut-actif';
                    }
                }
            ?>
            <tr>
                <td><?= htmlspecialchars($a['nom']) ?></td>
                <td><?= htmlspecialchars($a['email']) ?></td>
                <td><?= $age ?></td>
                <td><?= $a['date_inscription'] ?></td>
                <td class="<?= $classe_statut ?>"><?= $statut ?></td>
                <td>
                    <a href="modifier.php?id=<?= $a['id'] ?>">Modifier</a> |
                    <a href="supprimer.php?id=<?= $a['id'] ?>" onclick="return confirm('Supprimer cet adhérent ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
