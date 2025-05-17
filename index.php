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
    <header>
        <div class="logo">Fable</div>
        <nav>
            <ul>
                <li><a href="club.php">Clubs</a></li>
                <li><a href="search.php">Recherche</a></li>
                <li><a href="#">Blog</a></li>
                <li><a href="#">For Business</a></li>
                <li><a href="#">For Education</a></li>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <li><a href="login.html" class="login-btn">Sign In</a></li>
                    <li><a href="signup.html" class="signup-btn">Sign Up</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <div class="icons">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="profile.php"><i class="fas fa-user" id="user-icon"></i></a>
            <?php endif; ?>
        </div>
    </header>
    <main>
        <section class="hero">
            <div class="hero-text">
                <h2>WELCOME HOME</h2>
                <h1>Bookworms and binge-watchers</h1>
                <p>Join a community of good people to discuss great stories on Fable.</p>
                <button>Get the app</button>
            </div>
            <div class="hero-image">
                <img src="images/illustration.jpeg" alt="Illustration">
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
