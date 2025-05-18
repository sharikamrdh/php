<!-- index.php -->
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fable</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="script.js" defer></script>
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <section class="hero">
            <div class="hero-text">
                <h2>WELCOME HOME</h2>
                <h1>Bookworms and Book club</h1>
                <p>Rejoins la communauté pour discuter des livres!</p>
            </div>
            <div class="hero-image">
                <style>
                    .fondu {
                    width: 100%;
                    height: auto;
                    mask-image: linear-gradient(to bottom, black 60%, transparent 100%);
                    -webkit-mask-image: linear-gradient(to bottom, black 60%, transparent 100%);
                    }
                    </style>

                    <img src="images/illustration.jpeg" alt="Illustration" class="fondu">
            </div>
        </section>
        <section class="book-list">
            <div class="scrolling">
                <?php for ($i = 1; $i <= 8; $i++): ?>
                    <div class="book"><img src="images/book<?= $i ?>.jpg" alt="Book <?= $i ?>"></div>
                <?php endfor; ?>
            </div>
        </section>
        <section class="top-books">
            <h2>Top 3 par Genre</h2>
            <!-- Sections de genres à ajouter -->
        </section>
    </main>
</body>
</html>
