<?php
require_once 'includes/db.php';

$livres_data = [
    '1984' => [
        'image' => 'https://images-na.ssl-images-amazon.com/images/I/71kxa1-0zfL.jpg',
        'description' => 'Une dystopie classique décrivant un monde sous surveillance totale.'
    ],
    'Le Petit Prince' => [
        'image' => 'https://images-na.ssl-images-amazon.com/images/I/71X5FctDPzL.jpg',
        'description' => 'Un conte poétique et philosophique intemporel sur l’amitié, l’amour et l’essentiel.'
    ],
    'Les Misérables' => [
        'image' => 'https://images-na.ssl-images-amazon.com/images/I/81n1B4ZKnQL.jpg',
        'description' => 'Une fresque sociale bouleversante sur la misère, l’injustice et la rédemption.'
    ],
    'L’Étranger' => [
        'image' => 'https://images-na.ssl-images-amazon.com/images/I/71vRkRWUgzL.jpg',
        'description' => 'Un roman court, brut, sur l’absurde de la vie et l’indifférence humaine.'
    ],
    'Harry Potter à l’école des sorciers' => [
        'image' => 'https://images-na.ssl-images-amazon.com/images/I/81YOuOGFCJL.jpg',
        'description' => 'Le premier tome d’une saga magique incontournable qui a marqué toute une génération.'
    ]
];

foreach ($livres_data as $titre => $infos) {
    $stmt = $pdo->prepare("UPDATE livres SET image = ?, description = ? WHERE titre = ?");
    $stmt->execute([$infos['image'], $infos['description'], $titre]);
}

echo "✅ Descriptions et images ajoutées avec succès !";
?>
