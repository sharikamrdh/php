<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

$pdo = new PDO("mysql:host=localhost;dbname=fable_db;charset=utf8", "root", "");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $pdo->prepare("SELECT * FROM livres WHERE id = ?");
$stmt->execute([$id]);
$livre = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $livre ? htmlspecialchars($livre['title']) : 'Livre introuvable' ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php if ($livre): ?>
    <div class="profile-section">
        <div class="livre-card">
            <img src="<?= htmlspecialchars($livre['image_url']) ?>" alt="<?= htmlspecialchars($livre['title']) ?>">
            <div>
                <h1><?= htmlspecialchars($livre['title']) ?></h1>
                <p><strong>Auteur :</strong> <?= htmlspecialchars($livre['auteur']) ?></p>
                <p><strong>Résumé :</strong><br><?= nl2br(htmlspecialchars($livre['description'])) ?></p>
            </div>
        </div>
    </div>
<?php else: ?>
    <p style="text-align: center;">Aucun livre trouvé avec cet ID.</p>
<?php endif; ?>
</body>
</html>
