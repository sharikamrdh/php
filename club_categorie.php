<?php
session_start();
$pdo = new PDO("mysql:host=localhost;dbname=fable_db;charset=utf8", "root", "");

$categorie = $_GET['categorie'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM livres WHERE genre = ?");
$stmt->execute([$categorie]);
$livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Club <?= htmlspecialchars($categorie) ?></title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <section class="club-banner">
            <h1>Club de lecture : <?= htmlspecialchars($categorie) ?></h1>
            <p>Découvrez tous les livres de cette catégorie.</p>
        </section>

        <section id="search-results">
            <?php if (count($livres) > 0): ?>
                <div class="book-cards">
                    <?php foreach ($livres as $livre): ?>
                        <div class="book-card fade-in">
                            <a href="club_livre.php?id=<?= $livre['id'] ?>" class="book-link">
                                <img src="<?= $livre['image_url'] ?: 'images/defaut.jpg' ?>" alt="<?= htmlspecialchars($livre['title']) ?>">
                                <div class="book-info">
                                    <p><strong><?= htmlspecialchars($livre['title']) ?></strong></p>
                                    <p><?= htmlspecialchars($livre['auteur']) ?></p>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="error">Aucun livre trouvé dans cette catégorie.</p>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>



